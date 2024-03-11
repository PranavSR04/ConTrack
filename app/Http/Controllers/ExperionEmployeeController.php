<?php

namespace App\Http\Controllers;

use App\ServiceInterfaces\ExperionEmployeesInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ExperionEmployees;

class ExperionEmployeeController extends Controller
{

    private $experionEmployeesService;
    public function __construct(ExperionEmployeesInterface $experionEmployeesService)
    {
        $this->experionEmployeesService = $experionEmployeesService;
    }

    /**
     * Store a new record in the ExperionEmployees table.
     *
     * Validates and processes the incoming request data to create a new record
     * in the ExperionEmployees table. Requires the following parameters in the request:
     *
     * - email_id (string, required, valid email format)
     * - password (string, required, 6 to 30 characters)
     * - first_name (string, required, 3 to 20 characters)
     * - middle_name (string, optional, up to 20 characters)
     * - last_name (string, optional, up to 20 characters)
     *
     * Returns a success message upon successful data insertion or validation errors.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|array
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email_id" => "required|email",
            "password" => "required|min:6|max:30|string",
            "first_name" => "required|string|min:3|max:20",
            "middle_name" => "string|max:20",
            "last_name" => "string|max:20",
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        ExperionEmployees::create([
            "email_id" => $request->get("email_id"),
            "password" => $request->get("password"),
            "first_name" => $request->get("first_name"),
            "middle_name" => $request->get("middle_name"),
            "last_name" => $request->get("last_name"),
        ]);

        return "Data inserted successfully for Experion table";
    }

    /**
     * Store generated data to fill into the table
     */
    public function generateRandomData()
    {
        $dataArray = [
            [
                "email_id" => "john.smith@experionglobal.com",
                "password" => bcrypt("password1"),
                "first_name" => "John",
                "middle_name" => "Doe",
                "last_name" => "Smith",
            ],
            [
                "email_id" => "jane.johnson@experionglobal.com",
                "password" => bcrypt("password2"),
                "first_name" => "Jane",
                "middle_name" => "Alice",
                "last_name" => "Johnson",
            ],
            [
                "email_id" => "michael.brown@experionglobal.com",
                "password" => bcrypt("password3"),
                "first_name" => "Michael",
                "middle_name" => "",
                "last_name" => "Brown",
            ],
            [
                "email_id" => "emily.taylor@experionglobal.com",
                "password" => bcrypt("password4"),
                "first_name" => "Emily",
                "middle_name" => "Grace",
                "last_name" => "Taylor",
            ],
            [
                "email_id" => "william.jones@experionglobal.com",
                "password" => bcrypt("password5"),
                "first_name" => "William",
                "middle_name" => "",
                "last_name" => "Jones",
            ],
            [
                "email_id" => "olivia.anderson@experionglobal.com",
                "password" => bcrypt("password6"),
                "first_name" => "Olivia",
                "middle_name" => "Mae",
                "last_name" => "Anderson",
            ],
            [
                "email_id" => "ethan.miller@experionglobal.com",
                "password" => bcrypt("password7"),
                "first_name" => "Ethan",
                "middle_name" => "",
                "last_name" => "Miller",
            ],
            [
                "email_id" => "dantus.tom@experionglobal.com",
                "password" => bcrypt("password8"),
                "first_name" => "Dantus",
                "middle_name" => "George",
                "last_name" => "Tom",
            ],
            [
                "email_id" => "abhi.j@experionglobal.com",
                "password" => bcrypt("password9"),
                "first_name" => "Abhi",
                "middle_name" => "",
                "last_name" => "J",
            ]
        ];

        foreach ($dataArray as $data) {
            ExperionEmployees::create($data);
        }

        return "Data inserted successfully for Experion table";
    }

    /**
     * Display a listing of users based on the provided name.
     *
     * This function retrieves a list of users from the ExperionEmployees model
     * whose first_name, middle_name, or last_name partially match the provided name.
     * The request must include a 'name' parameter. Returns a JSON response
     * with the list of matching users or appropriate error messages.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        return $this->experionEmployeesService->show($request);
    }
}
