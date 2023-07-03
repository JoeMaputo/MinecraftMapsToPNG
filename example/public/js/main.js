
var banners_script = document.createElement('script');
document.head.append(banners_script);

//banners_script.src = "end/banners.js";
//v = new Viewer('map_viewer', -2112, -4160, 0, 0);
//for (let a = 0; a < 3; a++) {
//    for (let b = 0; b < 4; b++) {
//        v.add_tile(new MapTile('end/' + a + '-' + b + '.png', a, b));
//    }
//}


const another_banners = [
    {"x":8340,"z":5880,"c":"black","title":"Особняк1"},
    {"x":13590,"z":5640,"c":"black","title":"Особняк2"},
    {"x":6710,"z":10790,"c":"black","title":"Особняк3"},
    {"x":-1780,"z":-660,"c":"cyan","title":""},
    {"x":-1500,"z":-870,"c":"cyan","title":""},
    {"x":-2390,"z":-760,"c":"cyan","title":""},
    {"x":-775,"z":-1015,"c":"cyan","title":""},
    {"x":-2280,"z":-1270,"c":"cyan","title":""},
    {"x":-1880,"z":-1330,"c":"cyan","title":""},
    {"x":-1290,"z":-1350,"c":"cyan","title":""},
    {"x":-790,"z":-1320,"c":"cyan","title":""},
    {"x":-135,"z":-1300,"c":"cyan","title":""},
    {"x":-2170,"z":-1735,"c":"cyan","title":""},
    {"x":-1780,"z":-1865,"c":"cyan","title":""},
    {"x":-1320,"z":-1880,"c":"cyan","title":""},
    {"x":-710,"z":-1670,"c":"cyan","title":""},
    {"x":-230,"z":-1690,"c":"cyan","title":""},
    {"x":55,"z":-1895,"c":"cyan","title":""},
    {"x":-2695,"z":-2345,"c":"cyan","title":""},
    {"x":-2310,"z":-2295,"c":"cyan","title":""},
    {"x":-1720,"z":-2280,"c":"cyan","title":""},
    {"x":-1145,"z":-2425,"c":"cyan","title":""},
    {"x":-905,"z":-2390,"c":"cyan","title":""},
    {"x":-360,"z":-2360,"c":"cyan","title":""},
    {"x":280,"z":-2390,"c":"cyan","title":""},
    {"x":230,"z":-2820,"c":"cyan","title":""},
    {"x":-375,"z":-2805,"c":"cyan","title":""},
    {"x":-935,"z":-2870,"c":"cyan","title":""},
    {"x":-1400,"z":-2920,"c":"cyan","title":""},
    {"x":-1800,"z":-2760,"c":"cyan","title":""},
    {"x":-2345,"z":-2920,"c":"cyan","title":""},
    {"x":-1750,"z":-4965,"c":"cyan","title":""},
    {"x":330,"z":-4520,"c":"cyan","title":""},
    {"x":885,"z":-4455,"c":"cyan","title":""},
    {"x":1240,"z":-4455,"c":"cyan","title":""},
    {"x":615,"z":-3850,"c":"cyan","title":""},
    {"x":-730,"z":1350,"c":"cyan","title":""},
    {"x":-425,"z":1335,"c":"cyan","title":""},
    {"x":345,"z":1270,"c":"cyan","title":""},
    {"x":-250,"z":1785,"c":"cyan","title":""},
    {"x":13350,"z":-2160,"c":"red","title":"Восстановленный портал из ада"},
];
banners_script.src = "over/banners.js";
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


