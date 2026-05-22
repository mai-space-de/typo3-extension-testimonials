# mai_testimonials — Feature Reference

## Testimonial Record

Each testimonial is stored in `tx_maitestimonials_testimonial` and carries six fields:

| Field | Type | Required | Notes |
|---|---|---|---|
| `quote` | `text` (rows 5) | ✔ | The testimonial text; used as search field |
| `author_name` | `input` (max 255) | ✔ | Record label in the list view; used as search field |
| `author_role` | `input` (max 255) | — | Role or title of the author (e.g. "CEO") |
| `organisation` | `input` (max 255) | — | Organisation the author represents; used as search field |
| `portrait` | FAL file reference (max 1) | — | Allowed formats: `jpg`, `jpeg`, `png`, `webp`, `svg` |
| `categories` | `category` (manyToMany) | — | Links to `sys_category` — no custom category table |

Records are sorted by the `sorting` field (TYPO3 manual drag-and-drop ordering).

## sys_category Integration

`mai_testimonials` follows the project-wide architecture rule: **no custom category table**.

- The `categories` relation uses TYPO3's built-in `CategoryConfig` (TCA field type `category`),
  which maps to the shared `sys_category` table.
- The Extbase domain model uses `TYPO3\CMS\Extbase\Domain\Model\Category` directly.
- The same `sys_category` tree is shared with `mai_news`, `mai_faq`, `mai_gallery`, and
  `mai_timeline`, enabling a single category hierarchy across all record types.
- Relations are stored in `sys_category_record_mm` (TYPO3 core table — no custom MM table).

## Content Element Plugins

Three Extbase plugins are registered, all in the `maispace_feature` backend group:

| CType identifier | Plugin name | Controller action | Rendering |
|---|---|---|---|
| `maispace_testimonials_list` | `MaiTestimonials_List` | `TestimonialsController::listAction` | Responsive grid/list |
| `maispace_testimonials_slider` | `MaiTestimonials_Slider` | `TestimonialsController::sliderAction` | Auto-playing carousel |
| `maispace_testimonials_single` | `MaiTestimonials_Single` | `TestimonialsController::singleAction` | Single featured quote |

All three share the same FlexForm (`TestimonialsPlugin.xml`) and the same TypoScript
base configuration (`plugin.tx_maitestimonials_list`), with Slider and Single inheriting
via TypoScript copy (`<`).

## Frontend Rendering

All three actions resolve testimonials via a shared `resolveTestimonials()` helper
that applies these four priority rules:

1. **Storage pages + category UID** — `findFromPagesByCategoryUid($pageUids, $categoryUid)`
2. **Storage pages only** — `findFromPages($pageUids)`
3. **Category UID only** — `findByCategoryUid($categoryUid)`
4. **Fallback** — `findAll()`

### `listAction` — Grid / List

Assigns to the `List.html` template:

| Variable | Type | Description |
|---|---|---|
| `testimonials` | `QueryResultInterface` | All resolved testimonials, sorted by `sorting ASC` |
| `settings` | `array` | Merged FlexForm and TypoScript settings |

Renders a `<ul class="mai-testimonials__list">` of `<li>` blockquote cards.
Also injects `testimonials.js` via `AssetCollector` (for accordion / filtering if added later).

### `sliderAction` — Auto-playing Carousel

Assigns to the `Slider.html` template:

| Variable | Type | Description |
|---|---|---|
| `testimonials` | `QueryResultInterface` | All resolved testimonials, sorted by `sorting ASC` |
| `settings` | `array` | Merged FlexForm and TypoScript settings |

Injects `testimonials.js` via `AssetCollector`. The JavaScript is responsible for all
slider behaviour (see the **Slider JavaScript** section below).

### `singleAction` — Featured Quote

Resolves the same testimonial set, then picks the first record:

```php
$testimonials = $this->resolveTestimonials($settings);
$testimonial = $testimonials->getFirst();
```

Assigns to the `Single.html` template:

| Variable | Type | Description |
|---|---|---|
| `testimonial` | `Testimonial\|null` | First resolved testimonial, or `null` if none |
| `settings` | `array` | Merged FlexForm and TypoScript settings |

Renders a `<blockquote class="mai-testimonial mai-testimonial--featured">` with a
larger portrait image (120×120 px vs 80×80 px in grid/slider).

## Slider JavaScript

`EXT:mai_testimonials/Resources/Public/JavaScript/testimonials.js` is an
IIFE (no external dependencies) loaded for both `listAction` and `sliderAction`.

### Activation

The script scans the DOM for `[data-testimonials-slider]` on page load.
A slider is only initialised when the track contains **two or more** slides.

### Required HTML Attributes

| Attribute | Purpose |
|---|---|
| `data-testimonials-slider` | Outer slider container — triggers initialisation |
| `data-testimonials-track` | Scroll track that wraps all slides |
| `data-testimonials-slide` | Each individual slide element |
| `data-testimonials-prev` | "Previous" navigation button |
| `data-testimonials-next` | "Next" navigation button |

### Active-Slide State

The currently visible slide carries the CSS class `mai-testimonials__slide--active`
and `aria-hidden="false"`. All other slides have `aria-hidden="true"` and no active class.
The first slide (`i.isFirst` in Fluid) receives the active class on initial render.

### Autoplay

| Behaviour | Value |
|---|---|
| Interval | 5 000 ms (5 seconds) |
| Direction | Forward (index + 1), wrapping |
| Pause trigger | `mouseenter` or `focusin` on the slider container |
| Resume trigger | `mouseleave` or `focusout` on the slider container |

### Manual Navigation

Clicking the previous or next button:
1. Stops the autoplay timer
2. Advances to the requested slide (wrapping at boundaries)
3. Restarts the autoplay timer

### Accessibility

- Each slide has `aria-hidden` toggled on transition.
- Prev/next buttons carry `aria-label` from `LLL:…:slider.prev` / `LLL:…:slider.next`
  (translated strings: "Previous testimonial" / "Next testimonial").
- Keyboard focus on any slider element pauses autoplay (`focusin` / `focusout` listeners).

## FlexForm Configuration

`Configuration/FlexForms/TestimonialsPlugin.xml` — one sheet ("General"):

| Field | Type | Default | Description |
|---|---|---|---|
| `settings.pages` | `group` (pages, max 20) | — | Storage page UIDs to load testimonials from |
| `settings.categoryUids` | `category` (manyToMany) | — | Filter by `sys_category` — see note below |
| `settings.limit` | `number` | `0` | Maximum records to return (0 = all) — see note below |

> **Note — `categoryUids` vs `categoryUid`**: The FlexForm stores the category
> selection as `settings.categoryUids` (plural), but `TestimonialsController` reads
> `$settings['categoryUid']` (singular). As a result, the FlexForm category picker
> has no effect unless a matching `settings.categoryUid` is provided via TypoScript
> `plugin.tx_maitestimonials_list.settings.categoryUid`. This is a known naming
> discrepancy to be resolved in a future task.

> **Note — `limit`**: The `settings.limit` FlexForm field and TypoScript default exist
> but are not yet consumed by any repository query. The controller currently returns
> all matching records regardless of this setting.

## TypoScript Configuration

### Constants

```
plugin.tx_maitestimonials_list {
    view {
        templateRootPath =
        partialRootPath =
        layoutRootPath =
    }
    persistence {
        storagePid =
    }
}
```

Default view paths (`0` slot) point to `EXT:mai_testimonials/Resources/Private/`.
Integrators override via slot `10`.

`plugin.tx_maitestimonials_slider` and `plugin.tx_maitestimonials_single` both
inherit their configuration from `plugin.tx_maitestimonials_list` via TypoScript copy.

### Settings Defaults

```
plugin.tx_maitestimonials_list.settings {
    limit = 0
}
```

## CSS Class Reference

No SCSS is bundled with this extension. Styles are added in `EXT:mai_theme`
(per the architecture constraint — `mai_assets` compiles SCSS exclusively).

### Container Classes

| Class | Element | Description |
|---|---|---|
| `.mai-testimonials` | Outer wrapper | Applied to all three views |
| `.mai-testimonials--grid` | Modifier | Active on the List/Grid view |
| `.mai-testimonials--slider` | Modifier | Active on the Slider view |
| `.mai-testimonials--single` | Modifier | Active on the Single view |
| `.mai-testimonials__empty` | `<p>` | Shown when no testimonials are found |

### Grid / List Classes

| Class | Element | Description |
|---|---|---|
| `.mai-testimonials__list` | `<ul>` | Grid list container |
| `.mai-testimonials__item` | `<li>` | Individual list item |

### Slider Classes

| Class | Element | Description |
|---|---|---|
| `.mai-testimonials__track` | `<div>` | Scroll track wrapping all slides |
| `.mai-testimonials__slide` | `<div>` | Individual slide |
| `.mai-testimonials__slide--active` | Modifier | Currently visible slide |
| `.mai-testimonials__controls` | `<div>` | Prev/next button wrapper |
| `.mai-testimonials__btn` | `<button>` | Navigation button base class |
| `.mai-testimonials__btn--prev` | Modifier | Previous button |
| `.mai-testimonials__btn--next` | Modifier | Next button |

### Testimonial Card Classes

| Class | Element | Description |
|---|---|---|
| `.mai-testimonial` | `<blockquote>` | Individual quote card |
| `.mai-testimonial--featured` | Modifier | Applied in the Single view |
| `.mai-testimonial__quote` | `<p>` | The quote text |
| `.mai-testimonial__footer` | `<footer>` | Portrait + citation wrapper |
| `.mai-testimonial__portrait` | `<img>` | Author portrait (80×80 in grid/slider, 120×120 in single) |
| `.mai-testimonial__author` | `<cite>` | Author attribution |
| `.mai-testimonial__name` | `<span>` | Author name |
| `.mai-testimonial__role` | `<span>` | Role / title (hidden when empty) |
| `.mai-testimonial__organisation` | `<span>` | Organisation (hidden when empty) |

## Database Tables

### `tx_maitestimonials_testimonial`

| Column | SQL type | Notes |
|---|---|---|
| `uid` | `int(11)` | Auto-increment primary key |
| `pid` | `int(11)` | Storage page |
| `quote` | `text` | Full quote text |
| `author_name` | `varchar(255)` | Record label |
| `author_role` | `varchar(255)` | Optional role or title |
| `organisation` | `varchar(255)` | Optional organisation name |
| `portrait` | `int(11) unsigned` | FAL file reference count |
| `categories` | `int(11) unsigned` | MM counter (TYPO3 standard) |
| `sorting` | `int(11)` | Manual ordering |
| Standard enableFields | — | `hidden`, `deleted`, `starttime`, `endtime`, `sys_language_uid`, … |

Relations are stored in `sys_category_record_mm` (TYPO3 core table — no custom MM table).

## Language Files

| File | Purpose |
|---|---|
| `Default/locallang.xlf` | Frontend labels: empty-state message, slider prev/next ARIA |
| `Default/locallang_tca.xlf` | Backend labels: TCA fields, FlexForm fields, plugin titles |
| `Default/de.locallang.xlf` | German translations |

## Site Set

The extension ships the Site Set `maispace/mai-testimonials` (label "MaiSpace Testimonials").
Include it in your site configuration to load TypoScript automatically:

```yaml
# config/sites/<site>/config.yaml
dependencies:
  - maispace/mai-testimonials
```

## Architecture Constraints

- **No custom category table.** Category taxonomy is handled exclusively via `sys_category`.
  Never add a `tx_maitestimonials_category` table or a custom MM join.
- **No SCSS in this extension.** Add all component styles to `EXT:mai_theme`.
  This extension ships only `testimonials.js`.
- **Single shared FlexForm.** All three content element CTypes use the same
  `TestimonialsPlugin.xml` FlexForm — changes affect all three plugins simultaneously.
- **Slider requires 2+ records.** The JavaScript will not initialise the carousel if
  fewer than two slides are present in the track.
