<?php

/*
 * This file is part of the Sylius package.
 * (c) Paweł Jędrzejewski
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Sylius\ShopApiPlugin\Command\Cart;

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;

final class ChangeItemQuantitySpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith('ORDERTOKEN', 1, 5);
    }

    function it_has_order_token(): void
    {
        $this->orderToken()->shouldReturn('ORDERTOKEN');
    }

    function it_has_product_code(): void
    {
        $this->itemIdentifier()->shouldReturn(1);
    }

    function it_has_quantity(): void
    {
        $this->quantity()->shouldReturn(5);
    }

    function it_throws_an_exception_if_quantity_is_not_less_then_1(): void
    {
        $this->beConstructedWith('ORDERTOKEN', 'T_SHIRT_CODE', 0);

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }
}
