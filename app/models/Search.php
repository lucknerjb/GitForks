<?php

/**
*
*/
class Search extends Eloquent {
  protected $table = 'searches';
  protected $fillable = array('user', 'repo', 'search_count');

  public function getFullNameAttribute() {
    return "{$this->user}/{$this->repo}";
  }
}
