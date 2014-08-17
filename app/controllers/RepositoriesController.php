<?php

/**
*
*/
class RepositoriesController extends Controller
{

  private function sort_repositories($array) {}

  public function index($user, $repository) {
    // Store the search in the db
    // @TODO: Move to model function
    $search = Search::firstOrNew(array('user' => $user, 'repo' => $repository));
    if (isset($search->id)) {
      $search->search_count += 1;
    } else {
      $search->search_count = 1;
    }
    $search->save();

    // Retrieve searches
    // @TODO: Move to model function
    $searches_count = Search::count();
    $limit = 5;
    if ($searches_count > 5 && $searches_count < 10) { $limit = 5; }
    else if ($searches_count > 10 && $searches_count < 15) { $limit = 10; }
    else if ($searches_count > 15 && $searches_count < 20) { $limit = 15; }
    else if ($searches_count > 25) { $limit = 25; }
    $top_searches = DB::table('searches')->orderBy('search_count', 'desc')->limit($limit)->get();

    // Fetch repo
    $response = GitHub::getHttpClient()->get("repos/{$user}/{$repository}/forks?per_page=100&sort=stargazers&order=desc");
    $repos    = Github\HttpClient\Message\ResponseMediator::getContent($response);

    $data = array();
    foreach($repos as $repo) {
      $tmp = array(
        'id' => $repo['owner']['id'],
        'avatar_url' => $repo['owner']['avatar_url'],
        'owner' => $repo['owner']['login'],
        'owner_id' => $repo['id'],
        'full_name' => $repo['full_name'],
        'description' => $repo['description'],
        'created' => $repo['created_at'],
        'stars' => $repo['stargazers_count'],
        'watchers' => $repo['watchers'],
        'forks' => $repo['forks'],
        'open_issues' => $repo['open_issues'],
        'last_push' => $repo['pushed_at']
      );

      // @TODO: In V2, show commit count in the past say 6-12 months and possible show as a graph on a per week basis
      // Get commits
      // $year_ago_today = date('c', strtotime('-3 years', strtotime(date('Y-m-d')))); // "c" format gives the date in ISO 8601, needed by github
      // echo "/repos/{$tmp['full_name']}/commits?since={$year_ago_today}";
      // $commits_response = GitHub::getHttpClient()->get("/repos/{$tmp['full_name']}/commits?since={$year_ago_today}");
      // $commits_response = GitHub::getHttpClient()->get("/repos/{$tmp['full_name']}/stats/commit_activity");
      // $commits = Github\HttpClient\Message\ResponseMediator::getContent($commits_response);
      // print_r($commits); die;

      $data[] = $tmp;
    }

    function cmp($a, $b) {
      if ($a == $b) { return 0; }
      return ($a['stars'] < $b['stars']) ? +1 : -1;
    };

    usort($data, 'cmp');

    // echo json_encode($data); die;

    $view = View::make('repositories.index')
      ->with('data', $data)
      ->with('top_searches', $top_searches);

    return $view;
  }
}
