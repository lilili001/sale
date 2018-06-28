<?php

namespace Modules\Sale\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterSaleSidebar implements \Maatwebsite\Sidebar\SidebarExtender
{
    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @param Authentication $auth
     *
     * @internal param Guard $guard
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function handle(BuildingSidebar $sidebar)
    {
        $sidebar->add($this->extendWith($sidebar->getMenu()));
    }

    /**
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.content'), function (Group $group) {
            $group->item(trans('sale::sales.title.sales'), function (Item $item) {
                $item->icon('fa fa-copy');
                $item->weight(10);
                $item->authorize(
                     /* append */
                );
                $item->item(trans('sale::saleorders.title.saleorders'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    //$item->append('admin.sale.saleorder.create');
                    $item->route('admin.sale.saleorder.index');
                    $item->authorize(
                        $this->auth->hasAccess('sale.saleorders.index')
                    );
                });
                $item->item(trans('sale::orderrefunds.title.orderrefunds'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.sale.orderrefund.create');
                    $item->route('admin.sale.orderrefund.index');
                    $item->authorize(
                        $this->auth->hasAccess('sale.orderrefunds.index')
                    );
                });
                $item->item(trans('sale::orderreturns.title.orderreturns'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.sale.orderreturn.create');
                    $item->route('admin.sale.orderreturn.index');
                    $item->authorize(
                        $this->auth->hasAccess('sale.orderreturns.index')
                    );
                });
                $item->item(trans('sale::comments.title.comments'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.sale.comment.create');
                    $item->route('admin.sale.comment.index');
                    $item->authorize(
                        $this->auth->hasAccess('sale.comments.index')
                    );
                });
                $item->item(trans('sale::orderreviews.title.orderreviews'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.sale.orderreview.create');
                    $item->route('admin.sale.orderreview.index');
                    $item->authorize(
                        $this->auth->hasAccess('sale.orderreviews.index')
                    );
                });
// append





            });
        });

        return $menu;
    }
}
