
var banners_script = document.createElement('script');
document.head.append(banners_script);

//banners_script.src = "end/banners.js";
//v = new Viewer('map_viewer', -2112, -4160, 0, 0);
//for (let a = 0; a < 3; a++) {
//    for (let b = 0; b < 3; b++) {
//        v.add_tile(new MapTile('end/' + a + '-' + b + '.png', a, b));
//    }
//}


banners_script.src = "over/banners.js";
//const another_banners = [
//    {"x":4875,"z":-1350,"c":"green","title":"jungle temple"},
//    {"x":5465,"z":-890,"c":"green","title":"jungle temple"},
//    {"x":5700,"z":-890,"c":"green","title":"jungle temple"},
//    {"x":5690,"z":-2000,"c":"green","title":"jungle temple"},
//    {"x":1930,"z":-660,"c":"cyan","title":"ancient city"},
//    {"x":2520,"z":-600,"c":"cyan","title":"ancient city"},
//    {"x":2420,"z":-2230,"c":"cyan","title":"ancient city"},
//    {"x":2820,"z":-2200,"c":"cyan","title":"ancient city"},
//    {"x":3160,"z":-2050,"c":"cyan","title":"ancient city"},
//    {"x":2490,"z":-2550,"c":"cyan","title":"ancient city"},
//    {"x":2700,"z":-2470,"c":"cyan","title":"ancient city"},
//];
var v = new Viewer('map_viewer', -6208, -8256, -383, -424);
for (let a = 0; a < 7; a++) {
    for (let b = 0; b < 7; b++) {
        v.add_tile(new MapTile('over/' + a + '-' + b + '.png', a, b));
    }
}
//for (let ab of another_banners) {
//    v.add_banner(ab);
//}


banners_script.onload = function () {
    for (let bi of banners_list) {
        v.add_banner(bi);
    }
}

setTimeout(function() {v.draw_tiles();}, 1000);


