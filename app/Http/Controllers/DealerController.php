<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dealer\StoreRequest;
use App\Http\Requests\Dealer\UpdateRequest;
use App\Models\Dealer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DealerController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $dealers = Dealer::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->orderBy('code')
            ->simplePaginate(10)
            ->withQueryString();

        return view('dealers.index', [
            'title' => 'Astra Report - Daftar Dealer',
            'dealers' => $dealers,
        ]);
    }

    public function create(): View
    {
        return view('dealers.create', [
            'title' => 'Astra Report - Tambah Dealer',
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        Dealer::create($request->validated());

        return redirect()->route('dealers.index')->with('success', 'Data dealer berhasil ditambahkan.');
    }

    public function show(Dealer $dealer): View
    {
        return view('dealers.show', [
            'title' => 'Astra Report - Detail Dealer',
            'dealer' => $dealer,
        ]);
    }

    public function edit(Dealer $dealer): View
    {
        return view('dealers.edit', [
            'title' => 'Astra Report - Edit Dealer',
            'dealer' => $dealer,
        ]);
    }

    public function update(UpdateRequest $request, Dealer $dealer): RedirectResponse
    {
        $dealer->update($request->validated());

        return redirect()->route('dealers.index')->with('success', 'Data dealer berhasil diperbarui.');
    }

    public function destroy(Dealer $dealer): RedirectResponse
    {
        if ($dealer->isInUse()) {
            return redirect()->route('dealers.index')
                ->with('error', 'Data dealer tidak dapat dihapus karena masih memiliki akun atau data pengumpulan.');
        }

        $dealer->delete();

        return redirect()->route('dealers.index')->with('success', 'Data dealer berhasil dihapus.');
    }
}
