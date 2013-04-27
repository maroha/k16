<h1>Results!</h1>
@foreach($results as $id => $result)
	<h2>{{{ $tests[$id]["name"] }}}</h2>
	@if($result["success"])
		<p>Test success!</p>
		<pre>{{ $result["log"] }}</pre>
	@else
		<p>Test failed</p>
		<pre>{{ $result["log"] }}</pre>
		<h3>{{ $result["error"]->getMessage() }}</h3>
		@if(isset($result["error"]->data["screenshot"]))
			<p>Screenshot:</p>
			<img src="data:image/png;base64,{{ $exception_results["value"]["screen"] }}" />
		@endif
		<pre>{{ var_dump(array_except($result["error"]->data, array("screenshot"))) }}</pre>
		@if($result["error"]->getPrevious())
			@if($result["error"]->getPrevious() instanceof WebDriverException)
				<?php $exception_results = $result["error"]->getPrevious()->getResults() ?>
				<h3>{{ $exception_results["state"] }}</h3>
				<p>Message:</p>
				{{ $exception_results["value"]["message"] }}
				<p>Screenshot:</p>
				<img src="data:image/png;base64,{{ $exception_results["value"]["screen"] }}" />
			@else
				<h3>{{ $result["error"]->getPrevious()->getMessage() }}</h3>
			@endif
		@endif
	@endif
@endforeach
