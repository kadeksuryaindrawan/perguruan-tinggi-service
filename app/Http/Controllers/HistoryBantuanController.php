<?php

namespace App\Http\Controllers;

use App\Models\HistoryBantuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class HistoryBantuanController extends Controller
{
    public function index(Request $request)
    {
        $histories = HistoryBantuan::where('user_id',$request->id)->orderBy('created_at','desc')->get();

        return view('historybantuan.index',compact('histories'));
    }

    public function detail_data(Request $request)
    {
        $history = HistoryBantuan::find($request->history_id);

        return view('historybantuan.detail', compact('history'));
    }

    public function edit_data(Request $request)
    {
        $history = HistoryBantuan::find($request->history_id);

        return view('historybantuan.edit',compact('history'));
    }

    public function edit_process(Request $request, $id)
    {
        $user_id = $id;
        $history = HistoryBantuan::find($request->history_id);
        $validator = Validator::make($request->all(), [
            'desa' => ['required', 'string', 'max:255'],
            'potensi' => ['required'],
            'permasalahan' => ['required'],
            'bantuan' => ['required'],
            'perguruan_tinggi' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $history->update([
                'desa' => $request->desa,
                'potensi' => $request->potensi,
                'permasalahan' => $request->permasalahan,
                'bantuan' => $request->bantuan,
                'perguruan_tinggi' => $request->perguruan_tinggi,
            ]);

            return redirect('/history/?id=' . $user_id)->with('success', 'Berhasil edit data history!');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete_process(Request $request, $id)
    {
        $user_id = $id;
        $history = HistoryBantuan::find($request->history_id);
        if($history->id_permasalahan != null){
            Http::post('http://127.0.0.1:8002/api/editStatusPermasalahanBelum/' . $history->id_permasalahan);
        }
        $history->delete();
        return redirect('/history/?id=' . $user_id)->with('success', 'Berhasil hapus data history!');
    }

    public function getData($id)
    {
        $histories = HistoryBantuan::where('id_desa', $id)->get();

        if (!$histories) {
            return response()->json(['error' => 'Permasalahan not found'], 404);
        }

        return response()->json($histories);
    }

    public function getDataDetail($id)
    {
        $history = HistoryBantuan::find($id);

        if (!$history) {
            return response()->json(['error' => 'Permasalahan not found'], 404);
        }

        return response()->json($history);
    }
}
