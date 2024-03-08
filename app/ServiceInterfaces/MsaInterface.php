<?php
namespace App\ServiceInterfaces;
use Illuminate\Http\Request;
interface MsaInterface
{
public function addMsa(Request $request,$user_id);
public function updateMsa(Request $request,$user_id);

public function MSAList(Request $request);
}