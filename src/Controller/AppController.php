<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: flow
 * Date: 4/17/16
 * Time: 7:31 PM
 */

namespace Sitemap\Controller;

use App\Controller\AppController as BaseAppController;

class AppController extends BaseAppController
{
    public function initialize(): void
    {
        parent::initialize();

        if ($this->components()->loaded('Auth')) {
            $this->components()->get('Auth')->allow();
        }
    }
}
