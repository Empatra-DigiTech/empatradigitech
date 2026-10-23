<?php

namespace App\Http\Controllers\Patra;

use App\Exceptions\UserFacingException;
use App\Helpers\ErrorHelper;
use App\Http\Controllers\Controller;
use App\Models\FinancePeriod;
use App\Models\FinanceTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Penutupan periode Buku Kas (per bulan). Hanya SuperAdmin (lihat routes).
 * Periode tertutup: transaksi bulan itu tidak bisa ditambah, diubah, dihapus, atau dipulihkan
 * sampai periode DIBUKA KEMBALI dengan alasan tertulis (alasan & pelakunya tercatat).
 */
class FinancePeriodController extends Controller
{
    public function index()
    {
        $periods = FinancePeriod::with(['closer', 'reopener'])->orderByDesc('period')->get()->map(function ($p) {
            $start = Carbon::createFromFormat('Y-m-d', $p->period . '-01')->startOfMonth();
            $agg = FinanceTransaction::query()->toBase()
                ->whereDate('date', '>=', $start->toDateString())
                ->whereDate('date', '<=', $start->copy()->endOfMonth()->toDateString())
                ->selectRaw("COUNT(*) AS jumlah")
                ->selectRaw("COALESCE(SUM(CASE WHEN type = 'masuk' THEN amount END), 0) AS masuk")
                ->selectRaw("COALESCE(SUM(CASE WHEN type = 'keluar' THEN amount END), 0) AS keluar")
                ->first();
            $p->summary = $agg;
            return $p;
        });

        return view('patra.pages.finance.period', [
            'periods' => $periods,
            'defaultPeriod' => now()->subMonth()->format('Y-m'),
            'maxPeriod' => now()->format('Y-m'),
        ]);
    }

    private function validatePeriod(Request $request, array $extra = []): array
    {
        $v = Validator::make($request->all(), array_merge([
            'period' => ['required', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
        ], $extra), [
            'period.required' => 'Periode harus dipilih',
            'period.regex' => 'Format periode tidak valid',
            'reason.required' => 'Alasan membuka kembali periode wajib diisi',
            'reason.min' => 'Alasan minimal 10 karakter',
            'reason.max' => 'Alasan maksimal 500 karakter',
        ]);

        if ($v->fails()) {
            throw new UserFacingException(implode(' ', $v->errors()->all()));
        }

        return $v->validated();
    }

    public function close(Request $request)
    {
        try {
            $data = $this->validatePeriod($request);

            if ($data['period'] > now()->format('Y-m')) {
                throw new UserFacingException('Periode di masa depan tidak bisa ditutup');
            }

            $period = FinancePeriod::firstOrNew(['period' => $data['period']]);

            if ($period->exists && $period->is_closed) {
                throw new UserFacingException('Periode ' . $data['period'] . ' sudah ditutup');
            }

            $period->fill([
                'is_closed' => true,
                'closed_at' => now(),
                'closed_by' => Auth::id(),
            ])->save();

            Log::info('Periode keuangan ditutup', ['period' => $data['period'], 'by' => Auth::id()]);
            alert()->html('Berhasil', 'Periode ' . $data['period'] . ' ditutup', 'success');
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            alert()->error('Gagal', ErrorHelper::userMessage($e));
        }

        return redirect()->route('patra.finance.period.index');
    }

    public function reopen(Request $request)
    {
        try {
            $data = $this->validatePeriod($request, ['reason' => ['required', 'string', 'min:10', 'max:500']]);

            $period = FinancePeriod::where('period', $data['period'])->where('is_closed', true)->first();

            if (!$period) {
                throw new UserFacingException('Periode ' . $data['period'] . ' tidak sedang ditutup');
            }

            $period->update([
                'is_closed' => false,
                'reopened_at' => now(),
                'reopened_by' => Auth::id(),
                'reopen_reason' => $data['reason'],
            ]);

            Log::warning('Periode keuangan DIBUKA KEMBALI', ['period' => $data['period'], 'by' => Auth::id(), 'reason' => $data['reason']]);
            alert()->html('Berhasil', 'Periode ' . $data['period'] . ' dibuka kembali', 'success');
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            alert()->error('Gagal', ErrorHelper::userMessage($e));
        }

        return redirect()->route('patra.finance.period.index');
    }
}
