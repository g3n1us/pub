tool.minDistance = 10;
tool.maxDistance = 45;

var path;
var text;
var pj = this.project;
var sel;
var test;

$(window).on('makePrint', function(event){
// 	$('.hidden-print').remove();
	var canvasWidth = 850;
	var canvasHeight = 1100;
	
	var point = new Point(0, 0);
	var size = new Size(848, 1098);
	var background = new Shape.Rectangle(point, size);
	background.fillColor = $('body').css('background-color');
	background.strokeWidth = 1;
    background.strokeColor = 'black';
	$.each(ch, function(k, v){

		if(v.text.length){
			addTextToCanvas(v);
		}
		else if(v.elem.src){
			
			if(v.elem.src.indexOf('svg') !== -1){
				addSvgToCanvas(v);
			}
			else
				addRasterToCanvas(v);
		}
	});
	
	var downloadbutton = $('<button type="button" />');
	downloadbutton.addClass('btn btn-black').css({position: "fixed", top: 0, right: 0}).text('Download').appendTo('body');
	downloadbutton.click(downloadcanvas);
	
	$('#printCanvas').fadeIn();
	$('body').css('background-color', 'transparent');
	//window.location.reload();


});

function downloadcanvas(){
	var blob = new Blob([pj.exportSVG({asString:true})], {type: "image/svg+xml;charset=utf-8"});
	saveAs(blob, $('title').text() + ".svg");
}


function addSvgToCanvas(v){
	var imgholder = new Path.Rectangle({
	    point: [v.position.left - v.offset.left, v.position.top - v.offset.top],
	    size: [v.position.width, v.position.height],
	});

	$.get(v.elem.src, function(data){
		var options = {};
		options.expandShapes = true;
		options.applyMatrix = true;
		var svg = pj.importSVG(data, options);
		svg.fitBounds(imgholder.bounds);
	});
}


function addRasterToCanvas(v){
// make a holder for it
	var imgholder = new Path.Rectangle({
	    point: [v.position.left - v.offset.left, v.position.top - v.offset.top],
	    size: [v.position.width, v.position.height],
	});
	var raster = new Raster(v.elem);
	raster.width = v.position.width * 5;
	raster.height = v.position.height * 5;
	raster.fitBounds(imgholder.bounds);
}


function addTextToCanvas(v){
	var textholder = new Path.Rectangle({
	    point: [v.position.left - v.offset.left, v.position.top - v.offset.top],
	    size: [v.position.width, v.position.height],
		fontSize: v.styles['font-size'],
	    
	});
	text = new PointText({
		point: [0, 0],
		fillColor: v.styles.color,
		fontFamily: v.styles.fontfamily,
		fontWeight: v.styles.fontweight,
		fontSize: v.styles.fontsize,
		content: v.text,
	});
	text.fitBounds(textholder.bounds);
}


/*
function onMouseDown(event) {
	var hitoptions = {fill: true, stroke: true, segments: true, tolerance: true};
	hitoptions.handles = true;
	test = pj.hitTest(event.point, hitoptions);
	console.log(test);
	if(test) {
		sel = test.item;
		sel.opacity = 0.5;
		sel.content = 'Yep';
		pj.deselectAll()
		sel.selected = true;
	}
	else{
		path = new Path();
		text = new PointText(event.point);
		text.fillColor = 'red';
	
		// Set the content of the text item:
		text.content = 'Hello world';
	
		path.fillColor = {
			hue: Math.random() * 360,
			saturation: 1,
			brightness: 1
		};
	
		path.add(event.point);		
	}

}

function onMouseDrag(event) {
	if(!test) {	
		var step = event.delta / 2;
		step.angle += 90;
		
		var top = event.middlePoint + step;
		var bottom = event.middlePoint - step;
		
		path.add(top);
		path.insert(0, bottom);
		path.smooth();
	}
}

function onMouseUp(event) {
	console.log(test);
	if(!test) {
		path.add(event.point);
		path.closed = true;
		path.smooth();
		//pj = this.project;
		$('canvas').after(pj.exportSVG());
	}
}
*/
				
// alert(paper);				
