
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
const another_banners = [
    {"x":-6120,"z":-4820,"c":"black","title":"Особняк"},
    {"x":8340,"z":5880,"c":"black","title":"Особняк"},
    {"x":13590,"z":5640,"c":"black","title":"Особняк"},
    {"x":6710,"z":10790,"c":"black","title":"Особняк"},
];
var v = new Viewer('map_viewer', -6208, -8256, -383, -424);
for (let a = 0; a < 9; a++) {
    for (let b = 0; b < 7; b++) {
        v.add_tile(new MapTile('over/' + a + '-' + b + '.png', a, b));
    }
}
for (let ab of another_banners) {
    v.add_banner(ab);
}


banners_script.onload = function () {
    for (let bi of banners_list) {
        v.add_banner(bi);
    }
}

setTimeout(function() {v.draw_tiles();}, 1000);


