<?php

/**
*
*/
class PagesController extends Controller
{

  // protected $layout = 'layouts.default';

  public function index() {
    $view = View::make('pages.index');
    return $view;
  }
}
