<?php

class gitHub {
	private $user;

	function __construct($user) {
		$this->user=$user;
	}

	public function getRepos() {
		$query="http://github.com/api/v2/json/repos/show/".$this->user;
		$reply=json_decode(file_get_contents($query));
	
		if ($reply)
			foreach ($reply->repositories as $repo)
				$repos[]=$repo->name;
		
		return $repos;
	}

	public function getCommits($repo,$branch="master") {
		$query="http://github.com/api/v2/json/commits/list/".$this->user."/".$repo."/".$branch;
		$reply=json_decode(file_get_contents($query));
		
		if ($reply)
			foreach ($reply->commits as $commit)
				$out[]=array(
					"author"=>$commit->author->name,
					"ts"=>strtotime($commit->committed_date),
					"message"=>$commit->message,
				);
		
		return $out;
	}
	
	public function getAllCommits() {
		$repos=$this->getRepos();
		
		if ($repos)
			foreach ($repos as $repo)
				$out[$repo]=$this->getCommits($repo);
				
		return $out;
	}
	
	public function getFlatCommits() {
		$repos=$this->getRepos();
		
		if ($repos)
			foreach ($repos as $repo) {
				$commits=$this->getCommits($repo);
				if ($commits)
					foreach ($commits as $commit)
						$out[$commit["ts"]]=array(
							"repo"=>$repo,
							"author"=>$commit["author"],
							"message"=>$commit["message"],
						);
			}
		
		if (is_array($out))
			krsort($out);
			
		return $out;
	}
}
