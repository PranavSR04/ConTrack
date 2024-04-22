<?php
namespace App\ServiceInterfaces;
use Illuminate\Http\Request;
interface MsaInterface
{

     /**
     * Add a new MSA.
     *
     * @param Request $request The HTTP request containing MSA data.
     * @param int|null $user_id The ID of the user adding the MSA (optional).
     * @return mixed The result of adding the MSA.
     */
public function addMsa(Request $request,$user_id);

     /**
     * Edit an existing MSA.
     *
     * @param Request $request The HTTP request containing updated MSA data.
     * @param int|null $user_id The ID of the user editing the MSA (optional).
     * @return mixed The result of editing the MSA.
     */
public function editMsa(Request $request,$user_id);


     /**
     * Renew an existing MSA.
     *
     * @param Request $request The HTTP request containing data for MSA renewal.
     * @param int|null $user_id The ID of the user renewing the MSA (optional).
     * @return mixed The result of renewing the MSA.
     */
public function renewMsa(Request $request,$user_id);

     /**
     * Retrieve a list of MSAs based on the provided parameters.
     *
     * @param Request $request The HTTP request containing parameters for filtering/sorting.
     * @return mixed The list of MSAs.
     */
public function MSAList(Request $request);

     /**
     * Get the count of MSAs based on the provided parameters.
     *
     * @param Request $request The HTTP request containing parameters for filtering.
     * @return mixed The count of MSAs.
     */
public function msaCount(Request $request);
public function msaPage($id);
}