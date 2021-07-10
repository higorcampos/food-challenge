<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\FoodCreateRequest;
use App\Http\Requests\FoodUpdateRequest;
use App\Repositories\FoodRepository;
use App\Validators\FoodValidator;

/**
 * Class FoodController.
 *
 * @package namespace App\Http\Controllers;
 */
class FoodController extends Controller
{
    /**
     * @var FoodRepository
     */
    protected $repository;

    /**
     * @var FoodValidator
     */
    protected $validator;

    /**
     * FoodController constructor.
     *
     * @param FoodRepository $repository
     * @param FoodValidator $validator
     */
    public function __construct(FoodRepository $repository, FoodValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_page()
    {
        return view('home');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FoodCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(FoodCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $food = $this->repository->create($request->all());

            $response = [
                'message' => 'Obrigado agora sabemos que você gosta de '.$request['food'].'.',
                'data'    => $food->toArray(),
            ];


            return response()->json($response);
            
        } catch (ValidatorException $e) {
    
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ],400);
            

   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $foods = $this->repository->all()->toArray();
        $aux = [];

      
        if(empty($foods)){
            $aux = ['Lasanha'];
        }
        
        foreach($foods as $food){
            $aux[] = $food['food'];
        }

        return response()->json(['data' => $aux[rand(0, count($aux) - 1)]]);

    }


}
