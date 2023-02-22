<?php

namespace Formgeflecht\FilamentNestedRecords\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasNestedRecords
{

  /**
   * This method is called upon instantiation of the Eloquent Model.
   * It adds the "seoMeta" field to the "$fillable" array of the model.
   *
   * @return void
   */
  public function initializeIsNestedTrait()
  {
    $this->fillable[] = 'parent_id';
  }

  /**
   * Get parent models
   *
   * @return Builder
   */
  public function parent(): BelongsTo
  {
    return $this->belongsTo($this, 'parent_id');
  }

  /**
   * Get child models
   *
   * @return Builder
   */
  public function children(): HasMany
  {
    return $this->hasMany($this, 'parent_id');
  }

  /**
   * Check if is model is child
   *
   * @return boolean
   */
  public function isChild(): bool
  {
    return !is_null($this->parent_id);
  }

  /**
   * Get nested level of model
   *
   * @return integer
   */
  public function level(): int
  {
    return $this->getLevel($this);
  }

  /**
   * Get nested level for given item
   *
   * @param  [type]  $item
   * @param  integer $level
   * @return integer
   */
  protected function getLevel($item, $level = 0): int
  {
    $level++;

    if ($parent = $item->parent) {
      if ($item->parent->isChild()) {
        $level = $this->getLevel($item->parent, $level);
      }
    }

    return $level;
  }
}
