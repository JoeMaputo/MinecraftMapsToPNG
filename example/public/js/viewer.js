
const minecraft_colors = {
    white : 'white',
    light_gray: '#999',
    gray: '#555',
    black: '#222',
    yellow: 'yellow',
    orange: '#ff881d',
    red: 'red',
    brown: '#6c2b00',
    lime: 'lime',
    green: 'green',
    light_blue: '#3ab3da',
    cyan: '#007878',
    blue: 'blue',
    pink: '#ff87be',
    magenta: 'magenta',
    purple: 'purple'
};


class MapTile
{
    constructor(tile_url, px, py)
    {
        this.img = new Image();
        this.loaded = false;
        this.img.src = tile_url;
        this.px = px;
        this.py = py;
        let that = this;
        this.img.onload = function () {that.loaded = true;};
    }
}



class Viewer
{
    constructor(canvas_id, x0, z0, cx, cz)
    {
        this.canvas = document.getElementById(canvas_id);
        this.context = this.canvas.getContext("2d");
        this.article = document.createElement('article');
        this.article.setAttribute('class', 'coordinates');
        document.body.append(this.article);
        this.canvas.width = document.body.scrollWidth;
        this.canvas.height = Math.round(window.screen.availHeight * 0.9);
        this.context.font = '12px sans';
        this.context.textAlign = 'center';
        this.min_zoom_divider = 1;
        this.max_zoom_divider = 32;
        this.multipler = 4;
        this.zoom_divider = 4;
//        this.moved = false;
        this.x0 = x0;
        this.z0 = z0;
        this.dx = this.canvas.offsetWidth / 2 - this.multipler * (cx - x0) / this.zoom_divider;
        this.dy = this.canvas.offsetHeight / 2 - this.multipler * (cz - z0) / this.zoom_divider;;
        this.tiles = [];
        this.banners = [];
        this.setup();
    }
    
    add_tile(tile)
    {
        this.tiles.push(tile);
    }
    
    add_banner(banner)
    {
        this.banners.push(banner);
    }
    
    setup()
    {
        let that = this;
        this.canvas.onmousewheel = function (evt) {
            let s = that.zoom_divider;
            that.scroll(evt.deltaY > 0);
            that.clear();
            that.dx = evt.offsetX - Math.floor(s * (evt.offsetX - that.dx) / that.zoom_divider);
            that.dy = evt.offsetY - Math.floor(s * (evt.offsetY - that.dy) / that.zoom_divider);
            that.draw_tiles();
        };
        this.canvas.onmousemove = function (evt) {
            if (evt.buttons & 1) {
                that.dx += evt.movementX;
                that.dy += evt.movementY;
                that.clear();
                that.draw_tiles();
            } else {
                let cur_x = Math.floor(that.x0 + (evt.offsetX - that.dx) * that.zoom_divider / that.multipler);
                let cur_z = Math.floor(that.z0 + (evt.offsetY - that.dy) * that.zoom_divider / that.multipler);
                that.article.innerText = 'x: ' + cur_x + ' z: ' + cur_z;
            }
        }
    }
    
    scroll(up = false)
    {
        this.zoom_divider += up ? 1 : -1;
        if (this.zoom_divider < this.min_zoom_divider) {
            this.zoom_divider = this.min_zoom_divider;
        }
        if (this.zoom_divider > this.max_zoom_divider) {
            this.zoom_divider = this.max_zoom_divider;
        }
    }
    
    draw_tiles()
    {
        this.context.imageSmoothingEnabled = (this.zoom_divider > this.multipler);
        for (let i = 0; i < this.tiles.length; i++) {
            this.draw_tile(this.tiles[i]);
        }
        for (let bnr of this.banners) {
            this.draw_banner(bnr.x, bnr.z, bnr.c, bnr.title);
        }
    }
    
    draw_tile(tile)
    {
        if (!tile.loaded) {
            return;
        }
        let size = Math.round(this.multipler * tile.img.width / this.zoom_divider);
        this.context.drawImage(tile.img, this.dx + size * tile.px, this.dy + size * tile.py, size, size);
        
    }
    
    draw_banner(x, z, color = 'light_blue', title = '')
    {
        let radius = 8;
        let c_radius = 5;
        let ix = Math.floor(this.multipler * (x - this.x0) / this.zoom_divider + this.dx);
        let iy = Math.floor(this.multipler * (z - this.z0) / this.zoom_divider + this.dy);
        
        let dy = Math.round(3 * radius / 2);
        let dx = Math.round(dy / Math.sqrt(3));
        
        this.context.beginPath();
        this.context.moveTo(ix, iy);
        this.context.lineTo(ix + dx, iy - dy);
        this.context.arcTo(ix + 2 * dx, iy - 2 * dy, ix, iy - 3 * radius, radius);
        this.context.arcTo(ix - 2 * dx, iy - 3 * radius, ix - dx, iy - dy, radius);
        this.context.lineTo(ix - dx, iy - dy);
        this.context.closePath();
        this.context.fillStyle = 'black';
        this.context.fill();
        
        this.context.beginPath();
        this.context.arc(ix, iy - 2 * radius, c_radius, 0, 2 * Math.PI);
        this.context.fillStyle = minecraft_colors[color];
        this.context.fill();
        
        this.context.fillStyle = 'black';
        this.context.fillText(title, ix, iy + 16);
    }
    
    clear()
    {
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }
}

