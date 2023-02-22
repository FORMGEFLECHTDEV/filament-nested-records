<?php

namespace Formgeflecht\FilamentNestedRecords\Resources\Pages;

use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

use Illuminate\Support\LazyCollection;

class NestedRecords extends ListRecords
{

  protected function getTableQuery(): Builder
  {
    return static::getResource()::getEloquentQuery();
  }

  protected function getTableColumns(): array
  {
    $columns = collect(parent::getTableColumns());

    if ($titleColumn = $columns->firstWhere(fn ($col) => $col->getName() === config('filament-nested-records.title_column', 'title'))) {
      $titleColumn->formatStateUsing(fn ($state, $record) => $this->formatNestedColumn($state, $record));
    }

    return $columns->toArray();
  }

  public function getTableRecords(): EloquentCollection | Paginator
  {
    if ($this->records) {
      return $this->records;
    }

    $query = $this->getFilteredTableQuery();

    $this->applySortingToTableQuery($query);

    if (
      (!$this->isTablePaginationEnabled()) ||
      ($this->isTableReordering() && (!$this->isTablePaginationEnabledWhileReordering()))
    ) {
      return $this->records = $this->hydratePivotRelationForTableRecords($this->getNestedRecords($query->get()));
    }

    return $this->records = $this->hydratePivotRelationForTableRecords($this->paginateTableQuery($query));
  }

  protected function paginateTableQuery(Builder $query): Paginator
  {
    /** @var LengthAwarePaginator $records */

    $records = $this->getNestedRecords($query->get());

    $records = $records->paginate($this->getTableRecordsPerPage() === -1 ? $records->count() : $this->getTableRecordsPerPage());

    return $records->onEachSide(1);
  }

  /**
   * Get records sorted by parent
   *
   * @param  [type] $records
   * @return void
   */
  protected function getNestedRecords($records): Collection | Paginator
  {
    $generator = function (EloquentCollection $level, int $currentLevel = 0) use ($records, &$generator) {
      // here we are sorting by 'id', but you can sort by another field
      foreach ($level->sortBy('id') as $item) {
        // yield a single item
        $item->level = $currentLevel;
        yield $item;
        // continue yielding results from the recursive call
        $currentLevel += 1;
        yield from $generator($records->where('parent_id', $item->id), $currentLevel);
      }
    };

    return LazyCollection::make(function () use ($records, $generator) {
      // yield from root level
      yield from $generator($records->where('parent_id', null));
    })->flatten()->collect();
  }

  protected function formatNestedColumn($state, $record)
  {
    if ($record->isChild()) {
      $dash = array_fill(0, $record->level(), '—');
      return sprintf('%s %s', implode(' ', $dash), $state);
    }
    return $state;
  }
}
