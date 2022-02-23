<?php

namespace App\Traits\Service;

use Illuminate\Http\JsonResponse;

/**
 * Custom Response
 */
trait ResponseTransform
{
    
    /**
     * response
     *
     * @param mixed $responseMessage
     * @param string $responseStatus
     * @param integer $responseCode
     * @param array $responseHeader
     * @param array $additionals
     * @return JsonResponse
     */
    public function response($responseMessage, string $responseStatus = 'success', int $responseCode = 200, array $responseHeader = [], array $additionals = []): JsonResponse
    {
        $response['status'] = $responseStatus;

        if ($responseStatus != 'success') :
            $responseCode = $responseCode == 200 ? 422 : $responseCode;
            $response['message']    = $responseMessage;
            $response['errors']     = [
                'message'   => [$responseMessage]
            ];
        else :
            $response['data']   = $responseMessage;
        endif;

        if (!empty($additionals)) :
            $response = array_merge($response, $additionals);
        endif;

        return response()->json($response, $responseCode, $responseHeader);
    }

    public function responseException(\Exception $e)
    {
        $statusCode     = ($e->getMessage() == 'Unauthorized') ? 401 : 422;
        $additionals    = [];
        $serviceTrace   = env('SERVICE_TRACE_EXCEPTION', false);

        if ($serviceTrace) :
            $file = $e->getFile();
            $line = $e->getLine();

            $serviceException = "app/Libraries/Services/Core/Exception.php";
            
            $extract = implode('/', array_slice(explode('/', $file), -5));

            if ($serviceException == $extract) :
                $trace  = $e->getTrace();
                $file   = $trace[0]['file'];
                $line   = $trace[0]['line'];
            endif;

            $additionals = [
                'trace' => [
                    'file'  => $file,
                    'line'  => $line
                ]
            ];
        endif;

        return $this->response($e->getMessage(), 'error', $statusCode, [], $additionals);
    }
}
