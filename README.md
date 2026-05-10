# Mai Testimonials

TYPO3 extension providing testimonial content elements with slider, grid, and single-quote layouts.

## Features

- Testimonial records with quote, author name, role, organisation, portrait image (FAL), and sys_category
- Auto-playing carousel / slider layout
- Responsive card grid layout
- Featured single-quote layout
- Category filtering
- Story Wall: community testimonials from `mai_account` moderation queue

## Requirements

- TYPO3 >= 14.1
- PHP >= 8.2
- `maispace/mai-base`

## Installation

```bash
composer require maispace/mai-testimonials
```

## Usage

Add one of the provided content elements to your page:

- **Testimonials: Slider** – auto-playing carousel of testimonials
- **Testimonials: Grid** – responsive card grid
- **Testimonials: Single** – featured single quote

## Development

Run QA tools from within the extension directory:

```bash
composer lint:check
composer lint:fix
composer test:unit
```
