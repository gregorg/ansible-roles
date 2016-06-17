<?php
# category of hosts to show on main page
{% for group in groups %}
{% if not group in ('all', 'ungrouped') %}
$CONFIG['cat']['{{ group }}'] = array(
	{% for host in groups[group] %}
	'{{ hostvars[host]["hostname_alias"] }}',
	{% endfor %}
);
{% endif %}
{% endfor %}

# default plugins to show on host page
$CONFIG['overview'] = array('load', 'cpu', 'memory', 'swap');


# browser cache time for the graphs (in seconds)
$CONFIG['cache'] = 90;

