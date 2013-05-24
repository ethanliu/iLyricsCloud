$(function() {
	$('.plugin-table .sorted_table').sortable({
		containerSelector: 'tbody',
		itemSelector: 'tr',
		placeholder: '<tr class="placeholder"/>',
		onDrag: function(item, position, _super) {
			item.css(position);
		},
		onDragStart: function (item, container, _super) {
			item.css({
				height: item.height(),
				width: item.width()
			});
			item.addClass("dragged");
			$("body").addClass("dragging");
		},
		onDrop: function(item, container, _super) {
			item.removeClass("dragged").attr("style","");
			$("body").removeClass("dragging");
			var plugins = '';
			$.each(container.el.find('td.plugin'), function(i, item) {
				plugins += $(item).text() + ',';
			});
			$.post("index.php", {q:'plugins', action:'set', target:'ordering', value:plugins}, function(data) {
			}, 'json');
		}
	});

	$(".plugin-table input:checkbox.plugin").click(function() {
		var checked = ($(this).attr('checked') === undefined) ? "0" : "1";
		var value = $(this).val() + "|" + checked;
		$.post("index.php", {q:'plugins', action:'set', target:'enable', value:value}, function(data) {
		}, 'json');
	});
});

