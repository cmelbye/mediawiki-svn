%counts = ();

while (<>) {
	if ( !/\r/ && /^\w+:  (.{30})(.{40})(\d+)/ ) {
		$flags = $1;
		$class = $2;
		$count = $3;
		$flags =~ s/ +$//;
		$class =~ s/ +$//;

		$combined = "$flags/$class";
		if (!exists($counts{$combined})) {
			$counts{$combined} = 0;
		}
		$counts{$combined} += $count;
	}
}

@sortedTypes = sort( keys( %counts ) );
foreach my $type (@sortedTypes) {
	printf( "%-11d %s\n", $counts{$type}, $type );
}
