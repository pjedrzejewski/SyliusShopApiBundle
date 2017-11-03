<?php
declare(strict_types=1);

/**
 * Created by solutionDrive GmbH
 *
 * @author    lei wang <wang@solutiondrive.de>
 * @date      03.11.17
 * @time:     20:25
 * @copyright 2017 solutionDrive GmbH
 */

namespace Sylius\ShopApiPlugin\Controller\Product;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Sylius\ShopApiPlugin\ViewRepository\ProductHighlightViewRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ShowLatestProductAction
{
    /** @var ViewHandlerInterface */
    private $viewHandler;

    /** @var ProductHighlightViewRepository */
    private $productHighlightQuery;

    public function __construct(
        ViewHandlerInterface $viewHandler,
        ProductHighlightViewRepository $productHighlightQuery
    ) {
        $this->viewHandler = $viewHandler;
        $this->productHighlightQuery = $productHighlightQuery;
    }

    public function __invoke(Request $request): Response
    {
        if (!$request->query->has('channel')) {
            throw new NotFoundHttpException('Cannot find product without channel provided');
        }

        try {
            return $this->viewHandler->handle(View::create($this->productHighlightQuery->getLatestProducts(
                $request->query->get('channel'),
                $request->query->get('locale'),
                $request->query->get('limit', 4)
            ), Response::HTTP_OK));
        } catch (\InvalidArgumentException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }
    }
}