<?php

namespace Sylius\ShopApiPlugin\Factory;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\ShopApiPlugin\View\CartSummaryView;
use Sylius\ShopApiPlugin\View\TotalsView;
use Sylius\ShopApiPlugin\View\ItemView;

final class CartViewFactory implements CartViewFactoryInterface
{
    /**
     * @var CartItemViewFactoryInterface
     */
    private $cartItemFactory;

    /**
     * @var AddressViewFactoryInterface
     */
    private $addressViewFactory;

    /**
     * @var TotalViewFactoryInterface
     */
    private $totalViewFactory;

    /**
     * @param CartItemViewFactoryInterface $cartItemFactory
     * @param AddressViewFactoryInterface $addressViewFactory
     * @param TotalViewFactoryInterface $totalViewFactory
     */
    public function __construct(
        CartItemViewFactoryInterface $cartItemFactory,
        AddressViewFactoryInterface $addressViewFactory,
        TotalViewFactoryInterface $totalViewFactory
    ) {
        $this->cartItemFactory = $cartItemFactory;
        $this->addressViewFactory = $addressViewFactory;
        $this->totalViewFactory = $totalViewFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create(OrderInterface $cart, $localeCode)
    {
        $cartView = new CartSummaryView();
        $cartView->channel = $cart->getChannel()->getCode();
        $cartView->currency = $cart->getCurrencyCode();
        $cartView->locale = $localeCode;
        $cartView->checkoutState = $cart->getCheckoutState();
        $cartView->tokenValue = $cart->getTokenValue();
        $cartView->totals = $this->totalViewFactory->create($cart);

        /** @var OrderItemInterface $item */
        foreach ($cart->getItems() as $item) {
            $cartView->items[] = $this->cartItemFactory->create($item, $cart->getChannel(), $localeCode);
        }

        if (null !== $cart->getShippingAddress()) {
            $cartView->shippingAddress = $this->addressViewFactory->create($cart->getShippingAddress());
        }

        if (null !== $cart->getBillingAddress()) {
            $cartView->billingAddress = $this->addressViewFactory->create($cart->getBillingAddress());
        }

        return $cartView;
    }
}