var xms_{$name} = xmSelect.render({
	el: '#{$name}', 
	theme: {
		color: layui.data(layui.setter.tableName).theme.color.selected,
	},
	name: "{$name}",
	data: {:json_encode($options)},
	initValue: {$value|raw|default='[]'}
});