<?php

namespace Modules\Sale\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Sale\Events\Handlers\RegisterSaleSidebar;

class SaleServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        $this->app['events']->listen(BuildingSidebar::class, RegisterSaleSidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('saleorders', array_dot(trans('sale::saleorders')));
            $event->load('orderrefunds', array_dot(trans('sale::orderrefunds')));
            $event->load('orderreturns', array_dot(trans('sale::orderreturns')));
            $event->load('comments', array_dot(trans('sale::comments')));
            $event->load('orderreviews', array_dot(trans('sale::orderreviews')));
            // append translations





        });
    }

    public function boot()
    {
        $this->publishConfig('sale', 'permissions');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(
            'Modules\Sale\Repositories\SaleOrderRepository',
            function () {
                $repository = new \Modules\Sale\Repositories\Eloquent\EloquentSaleOrderRepository(new \Modules\Sale\Entities\SaleOrder());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Sale\Repositories\Cache\CacheSaleOrderDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Sale\Repositories\OrderRefundRepository',
            function () {
                $repository = new \Modules\Sale\Repositories\Eloquent\EloquentOrderRefundRepository(new \Modules\Sale\Entities\OrderRefund());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Sale\Repositories\Cache\CacheOrderRefundDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Sale\Repositories\OrderReturnRepository',
            function () {
                $repository = new \Modules\Sale\Repositories\Eloquent\EloquentOrderReturnRepository(new \Modules\Sale\Entities\OrderReturn());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Sale\Repositories\Cache\CacheOrderReturnDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Sale\Repositories\CommentRepository',
            function () {
                $repository = new \Modules\Sale\Repositories\Eloquent\EloquentCommentRepository(new \Modules\Sale\Entities\Comment());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Sale\Repositories\Cache\CacheCommentDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Sale\Repositories\OrderReviewRepository',
            function () {
                $repository = new \Modules\Sale\Repositories\Eloquent\EloquentOrderReviewRepository(new \Modules\Sale\Entities\OrderReview());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Sale\Repositories\Cache\CacheOrderReviewDecorator($repository);
            }
        );
// add bindings





    }
}
