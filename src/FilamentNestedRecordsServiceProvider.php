<?php

namespace Formgeflecht\FilamentNestedRecords;

use Filament\PluginServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Spatie\LaravelPackageTools\Package;

class FilamentNestedRecordsServiceProvider extends PluginServiceProvider
{
  public static string $name = 'filament-nested-records';

  public function configurePackage(Package $package): void
  {
    $package->name(static::$name)
      ->hasConfigFile()
      ->hasViews();
  }

  public function boot()
  {
    parent::boot();
    $this->setup();
  }

  protected function setup()
  {
    if (!Collection::hasMacro('paginate')) {

      Collection::macro(
        'paginate',
        function ($perPage = 15, $page = null, $options = []) {
          $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

          return (new LengthAwarePaginator(
            $this->forPage($page, $perPage),
            $this->count(),
            $perPage,
            $page,
            $options
          ))->withPath('');
        }
      );
    }
  }
}
