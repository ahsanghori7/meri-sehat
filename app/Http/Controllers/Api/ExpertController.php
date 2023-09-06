<?php   namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Experts\{CreateExpertRequest, UpdateExpertRequest};
use Illuminate\Http\Request;
use App\Models\{Expert, ExpertFollow};
use Carbon\Carbon;

class ExpertController extends Controller
{
    protected $expert;

    function __construct()
    {
        $this->expert = new Expert();
    }

    public function get(Request $request)
    {
        try{
            $data = $this->expert->listing($request);
            return $this->returnResponseWithListing(200, 'Experts Listing', $data);
        }catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        } 
    }

    public function detail(Request $request)
    {
        try{
            $expert = new Expert();
            $data = $expert->detail($request);
            return $this->returnResponse(200, 'Expert Detail', $data);
        }catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function store(CreateExpertRequest $request)
    {
        try{
            $data = $this->expert->add($request);
            return $this->returnResponse(201, 'Expert Create', $data);
        }catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function update(UpdateExpertRequest $request, $id)
    {
        try{
            $data = $this->expert->edit($request, $id);
            return $this->returnResponse(200, 'Expert Update', $data);
        }catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try{
            $data = $this->expert->deleteRecord($id);
            return $this->returnResponse(200, 'Expert delete', $data);
        }catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
}
