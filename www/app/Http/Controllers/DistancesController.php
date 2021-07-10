<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\DistancesCreateRequest;
use App\Http\Requests\DistancesUpdateRequest;
use App\Repositories\DistancesRepository;
use App\Validators\DistancesValidator;

/**
 * Class DistancesController.
 *
 * @package namespace App\Http\Controllers;
 */
class DistancesController extends Controller
{
    /**
     * @var DistancesRepository
     */
    protected $repository;

    /**
     * @var DistancesValidator
     */
    protected $validator;

    /**
     * DistancesController constructor.
     *
     * @param DistancesRepository $repository
     * @param DistancesValidator $validator
     */
    public function __construct(DistancesRepository $repository, DistancesValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $distances = $this->repository->paginate(1);
        return view('home', compact('distances'));
    }

    /**
     * Display form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  DistancesCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(DistancesCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $data = $request->all();

      
            $postcode_origin = $data['postcode_origin'];
            $postcode_destiny = $data['postcode_destiny'];
            
            $postcode_origin_uri = "https://www.cepaberto.com/api/v3/cep?cep=". $postcode_origin;
            $postcode_destiny_uri = "https://www.cepaberto.com/api/v3/cep?cep=". $postcode_destiny;
            
            $result_origin = $this->request_cep_aberto($postcode_origin_uri);
            $result_destiny = $this->request_cep_aberto($postcode_destiny_uri);

            if (!empty($postcode_origin) && !empty($postcode_destiny)){

                $calculated = [];

               
                if (!empty($result_origin) && !empty($result_destiny)){
                    $calculated = [
                        'lat_1'     =>$result_origin->latitude,
                        'long_1'    =>$result_origin->longitude,
                        'lat_2'     =>$result_destiny->latitude,
                        'long_2'    =>$result_destiny->longitude,
                    ];

                    $data['calculated_distance'] = $this->calculated_distance($calculated);

                }
               

                // return $this->request($uri);
            }


            $distance = $this->repository->create($data);

            $response = [
                'message' => 'Distances created.',
                'data'    => $distance->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect('/')->with('status', 'Updated!');

        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $distance = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $distance,
            ]);
        }

        return view('distances.show', compact('distance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $distance = $this->repository->find($id);

        return view('edit', compact('distance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  DistancesUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(DistancesUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

           
            $data = $request->all();

                  
            $postcode_origin = $data['postcode_origin'];
            $postcode_destiny = $data['postcode_destiny'];
            
            $postcode_origin_uri = "https://www.cepaberto.com/api/v3/cep?cep=". $postcode_origin;
            $postcode_destiny_uri = "https://www.cepaberto.com/api/v3/cep?cep=". $postcode_destiny;
            
            $result_origin = $this->request_cep_aberto($postcode_origin_uri);
            $result_destiny = $this->request_cep_aberto($postcode_destiny_uri);

            if (!empty($postcode_origin) && !empty($postcode_destiny)){

                $calculated = [];

               
                if (!empty($result_origin) && !empty($result_destiny)){
                    $calculated = [
                        'lat_1'     =>$result_origin->latitude,
                        'long_1'    =>$result_origin->longitude,
                        'lat_2'     =>$result_destiny->latitude,
                        'long_2'    =>$result_destiny->longitude,
                    ];

                    $data['calculated_distance'] = $this->calculated_distance($calculated);

                }
               

                // return $this->request($uri);
            }

            $distance = $this->repository->update($data, $id);

            $response = [
                'message' => 'Distances updated.',
                'data'    => $distance->toArray(),
            ];


            return redirect('/')->with('status', 'Updated!');
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Distances deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Distances deleted.');
    }

    /**
     * Request CEP Aberto
     *
     * @param $request
     *
     * @return \Illuminate\Http\Response
     */

    public function request_cep_aberto($uri) {
        if (!empty($uri)){
            try {
                $request = curl_init();
                curl_setopt ($request, CURLOPT_HTTPHEADER, array('Authorization: ' . 'Token token=a12946fcf67eba8e8d273c4462d89267')); // Access token for request.
                curl_setopt ($request, CURLOPT_URL, $uri); // Request URL.
                curl_setopt ($request, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt ($request, CURLOPT_CONNECTTIMEOUT, 5); // Connect time out.
                curl_setopt ($request, CURLOPT_CUSTOMREQUEST, 'GET'); // HTPP Request Type.
                $file_contents = curl_exec($request);
                curl_close($request);
        
                return json_decode($file_contents);

            } catch (ValidatorException $e){
                return $e->getMessage();
            }
        }
    }

    public function calculated_distance($data)
    {
      
        $rad = M_PI / 180;
        //Calculate distance from latitude and longitude
        $theta = $data['long_1'] - $data['long_2'];
        $dist = sin($data['lat_1'] * $rad) 
            * sin($data['lat_2'] * $rad) +  cos($data['lat_1'] * $rad)
            * cos($data['lat_2'] * $rad) * cos($theta * $rad);

        return acos($dist) / $rad * 60 *  1.853;
    }
}
