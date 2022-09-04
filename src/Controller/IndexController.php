<?php
/*
 *  * @copyright C UAB NFQ Technologies
 *  *
 *  * This Software is the property of NFQ Technologies
 *  * and is protected by copyright law – it is NOT Freeware.
 *  *
 *  * Any unauthorized use of this software without a valid license key
 *  * is a violation of the license agreement and will be prosecuted by
 *  * civil and criminal law.
 *  *
 *  * Contact UAB NFQ Technologies:
 *  * E-mail: info@nfq.lt
 *  * https://www.nfq.lt
 *
 */
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    public function indexAction(Request $request)
    {
        $result = [];
        $result['countries'] = ['LT', 'ES', 'DE'];
        return $this->render('countries/view.html.twig', ['countries' => $result]);
    }
}
