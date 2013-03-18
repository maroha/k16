<?php

class Base_Controller extends Controller {

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return Response::error('404');
	}

	// Overwrites response method to make ajax request
	public function response($method, $parameters = array()) {
		// Pass it to parent function
		$response = parent::response($method, $parameters);

		// If it's already a response, then probably no changes are needed.
		if($response instanceof Response) {
			return $response;
		}

		if(Request::ajax()) {
			$ajax_response = Response::make($response->content);
			$metadata = array(
				"menuItem" => null,
				"javascript" => $response->javascript
			);
			$ajax_response->header("K16-META", json_encode($metadata));
			return $ajax_response;
		} else {
			return $response;
		}
	}
}