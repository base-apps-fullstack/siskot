<?php

namespace App\Http\Controllers;

use App\Traits\Service\RequestHandler;
use App\Traits\Service\ResponseTransform;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    use ResponseTransform;
    use RequestHandler;

    /**
     * Get per page options
     *
     * @param Builder|Collection $data
     * @param integer $defaultPerPage
     * @return integer
     */
    public function perPage($data = null, $defaultPerPage = 15)
    {
        if (app('request')->get('page') == 'all') {
            return ($data instanceof Builder || $data instanceof Collection)
                ? $data->count()
                : $defaultPerPage;
        } else {
            return app('request')->filled('per_page')
                ? app('request')->get('per_page')
                : $defaultPerPage;
        }
    }
}
