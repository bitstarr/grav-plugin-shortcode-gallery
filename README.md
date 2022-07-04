# Shortcode Gallery Plugin

## About

The **Shortcode Gallery** Plugin is an extension for [Grav CMS](http://github.com/getgrav/grav). A shortcode extension to add simple and customizable galleries to your Grav website.

It is a fork of [Shortcode Gallery++](https://github.com/bitstarr/grav-plugin-shortcode-gallery) to fit my personal likings.

## Usage

It's quite simple. Just wrap some image links in `[gallery]` tags:

```markdown
[gallery]
![Alt text 1](image.jpg "Some description to be used in the lightbox")
![Alt text 2](/images/image.jpg "<strong>Descriptions</strong> can also<br>be <i>HTML</i> formatted.")
![relative link](../image.jpg)
![remote link](https://remotesite.com/image.jpg)
...
[/gallery]
```

## Okay, what does it look like?

This plugin combines a nice justified gallery layout with an eye-pleasing lightbox.
All images get nicely aligned. After a click on one of them, a sweet popup appears, showing it full-screen.
Just have a look for yourself:

![Demo](assets/demo.webp)

* You can of course create several galleries on the same page.
* You have plenty of settings you can change in the admin panel.
* You can also change everything for a single galleries via shortcode. For example:
```markdown
[gallery rowHeight=230 margins=25 lastRow="justify" captions="false" border=0]
![Alt text 1](image.jpg "Some description to be used in the lightbox")
![Alt text 2](/images/image.jpg "<strong>Descriptions</strong> can also<br>be <i>HTML</i> formatted.")
![relative link](../image.jpg)
![remote link](https://remotesite.com/image.jpg)
...
[/gallery]
```

## Gallery settings

| parameter   | possible values | description |
|-------------|-----------------| ------------|
| `rowHeight` | dimension in pixel | The preferred rows height.
| `margins`   | dimension in pixel | The margins between the images.
| `lastRow`   | `justify`, `hide`, `nojustify`, `center`, `right` | `justify`: justifies the last row; `hide`: hides the row if it can't be justified; `nojustify`: align the last row to the left; `center`: align the last row to the center; `right`: align the last row to the right
| `captions`  | `true`, `false` | Enable captions that appear when the mouse hovers an image. **For caption, the alt-text of an image is used: `![caption](image.jpg)`**
| `border`    | dimension in pixel | The border size of the gallery. With a negative value the border will be the same as `margins`.

## Lightbox settings

| parameter             | possible values | description |
|-----------------------|-----------------| ------------|
| `openEffect`          | `zoom`, `fade`, `none` |
| `closeEffect`         | `zoom`, `fade`, `none` |
| `slideEffect`         | `slide`, `zoom`, `fade`, `none` |
| `closeButton`         | `true`, `false` | Show or hide the close button.
| `touchNavigation`     | `true`, `false` | Enable touch navigation (swipe).
| `touchFollowAxis`     | `true`, `false` | Image follow axis when dragging on mobile.
| `keyboardNavigation`  | `true`, `false` | Enable or disable the keyboard navigation.
| `closeOnOutsideClick` | `true`, `false` | Close the lightbox when clicking outside the active slide.
| `loop`                | `true`, `false` | Loop slides on end.
| `draggable`           | `true`, `false` | Enable or disable mouse drag to go to previous and next slide.
| `descEnabled`         | `true`, `false` | **For description, the title-text of an image is used: `![](image.jpg "description")`**
| `descPosition`        | `bottom`, `top`, `left`, `right` | The position for slides description.
| `descMoreText`        | text            | Description: "See more" text.
| `descMoreLength`      | number          | Description: Characters until "See more". Will display the entire description, if set to `0`.


---

## Installation

This Plugin will not be published via GPM because it's meant to be a building block of your custom theme. It can run out of the box, but I prefer to use custom template, CSS and JS.

### Manual Installation

> NOTE: This plugin is a modular component for Grav which requires the [Grav Shortcode Core Plugin](https://github.com/getgrav/grav-plugin-shortcode-core) to be installed.

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `shortcode-gallery`. You can find these files on [GitHub](https://github.com/bitstarr/grav-plugin-shortcode-gallery).

## Configuration

Before configuring this plugin, you should copy
the `user/plugins/shortcode-gallery/shortcode-gallery.yaml`
to `user/config/plugins/shortcode-gallery.yaml` and only edit that copy.

**Preferably**, use the Admin Plugin. It takes care of creating a file with your configuration
named `shortcode-gallery.yaml` to be created in the `user/config/plugins/`-folder once the configuration is
saved in the Admin.

---

## Credits

* This plugin is based on [Shortcode Gallery++](https://github.com/bitstarr/grav-plugin-shortcode-gallery)

## Todo

* Site wide setting how to render inside feeds