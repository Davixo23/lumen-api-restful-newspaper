<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser
{
    /**
     * Build a success response
     * @param string|array $data 
     * @param int $code
     * @return Illuminate\Http\Response
     */
    public function successResponse($data, $code = Response::HTTP_OK){// respuesta en json
        return response($data, $code)->header('Content-Type', 'application/json');
    }

    /**
     * Build a valid response
     * @param string|array $data 
     * @param int $code
     * @return Illuminate\Http\Response
     */
    public function validResponse($data, $code = Response::HTTP_OK){// respuesta en json de una validacion
        return response()->json($data, $code);
    }

    /**
     * Build error responses
     * @param string $message
     * @param int $code
     * @return Illuminate\Http\JsonResponse
     */
    public function errorResponse($message, $code){// respuesta en json
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

}

?>