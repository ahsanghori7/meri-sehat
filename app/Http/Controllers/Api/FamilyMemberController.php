<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{Language, UserFamilyMember};
use App\Http\Resources\User\{FamilyMemberResource};
use Illuminate\Support\Facades\Validator;

class FamilyMemberController extends Controller
{
    /**
     * This method is used to Add new Family Members
     */
    public function createFamilyMember(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => ['required'],
                'phone' => ['required', 'regex:/(03)[0-9]{9}$/'],
                'email' => ['required', 'email'],
                'relationship' => ['required'],
                'image' => ['image', 'mimes:jpeg,png,jpg,JPEG,PNG,JPG,MPEG,heif,heic,heif-sequence,heic-sequence'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $userId = $this->getUserIdFromHeader($request->header());
            if($request->hasFile('image')){
                $this->uploadFile($request->file('image'), $userId);
                $input['image'] = $this->getFileName($request->file('image'));
            }
            if(UserFamilyMember::where('user_id', $userId)->count() == $this->getConstantByValue("TOTAL_FAMILY_MEMBERS_LIMIT")){
                return $this->returnResponse(400, 'You are already reached the limit of your family members.');
            }
            if(!$userId){
                return $this->returnResponse(400, 'Guest User are not allowed to creae a family member.');
            }
            $input['user_id'] = $userId;
            $getFamilyMember = UserFamilyMember::create($input);
            if(!$getFamilyMember){
                return $this->returnResponse(400, 'Unable to create new Family Member.');
            }
            if($request->header('locale') == Language::ENGLISH){
                return $this->returnResponse(200, 'Family member has been created successfully');
            }else{
                return $this->returnResponse(200, "فیملی ممبر کامیابی سے بن گیا ہے۔");
            }
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to Add new Family Members
     */
    public function updateFamilyMember(Request $request, UserFamilyMember $familyMember)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => ['required'],
                'phone' => ['required', 'regex:/(03)[0-9]{9}$/'],
                'email' => ['required', 'email'],
                'relationship' => ['required'],
                'image' => ['image', 'mimes:jpeg,png,jpg,JPEG,PNG,JPG,MPEG,heif,heic,heif-sequence,heic-sequence'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            if($request->hasFile('image') && $familyMember->image){
                $this->deleteFile($familyMember->image, $familyMember->user_id);
            }
            if($request->hasFile('image')){
                $this->uploadFile($request->file('image'), $familyMember->user_id);
                $input['image'] = $this->getFileName($request->file('image'));
            }
            $updateFamilyMember = $familyMember->update($input);
            if(!$updateFamilyMember){
                return $this->returnResponse(400, 'Unable to update family member.');
            }
            return $this->returnResponse(200, 'Family member has been updated successfully.', new FamilyMemberResource(UserFamilyMember::find($familyMember->id)));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to Add new Family Members
     */
    public function getFamilyMember(Request $request)
    {
        try {
            $userId = $this->getUserIdFromHeader($request->header());
            $getFamilyMembers = UserFamilyMember::where('user_id', $userId)->get();
            if(!$getFamilyMembers){
                return $this->returnResponse(400, 'No family member were found.');
            }
            return $this->returnResponse(200, '', FamilyMemberResource::collection($getFamilyMembers));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to Add new Family Members
     */
    public function deleteFamilyMember(Request $request, UserFamilyMember $familyMember)
    {
        try {
            $deleteFamilyMember = $familyMember->delete();
            if(!$deleteFamilyMember){
                return $this->returnResponse(400, 'Unable to delete family member.');
            }
            return $this->returnResponse(200, 'Family member has been deleted successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
}
