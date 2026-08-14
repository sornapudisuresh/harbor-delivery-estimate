# Harbor Delivery Estimate

WooCommerce plugin that shows a **configurable estimated delivery window** on product pages, cart, and checkout.

Helps shoppers set expectations before they buy. It does **not** calculate real carrier rates — merchants configure a business-day window (with an optional same-day cutoff).

## Features

- Minimum and maximum business-day window
- Same-day cutoff hour using the site timezone
- Optional display on product, cart, and checkout
- Settings under **WooCommerce → Settings → Products → Delivery estimate**
- Lightweight front-end CSS for the estimate notice

## Requirements

| Requirement | Version |
|---|---|
| WordPress | 6.4+ |
| PHP | 7.4+ |
| WooCommerce | Required (plugin will show an admin notice if inactive) |

## Installation

### From GitHub (manual)

1. Upload the `harbor-delivery-estimate` folder to `/wp-content/plugins/`
2. Activate **Harbor Delivery Estimate** under **Plugins**
3. Ensure WooCommerce is active
4. Configure under **WooCommerce → Settings → Products → Delivery estimate**

```bash
cd wp-content/plugins
git clone https://github.com/sornapudisuresh/harbor-delivery-estimate.git harbor-delivery-estimate
```

## Configuration

In **WooCommerce → Settings → Products → Delivery estimate** you can set:

- Minimum / maximum business days
- Same-day cutoff hour
- Where the estimate appears (product, cart, checkout)

## Plugin structure

```text
harbor-delivery-estimate/
├── assets/css/
├── includes/
│   ├── class-hde-settings.php
│   └── class-hde-display.php
├── harbor-delivery-estimate.php
├── readme.txt                 # WordPress.org-style readme
└── README.md                  # This file (GitHub)
```

## FAQ

### Does this calculate real carrier rates?

No. It shows a merchant-configured business-day window so customers know what to expect before checkout.

### Can I use this with Harbor Commerce?

Yes. It works with any WooCommerce storefront, including the companion theme [Harbor Commerce](https://github.com/sornapudisuresh/harbor-commerce).

## Author

**Sornapudi Suresh** · [GitHub](https://github.com/sornapudisuresh) · [WordPress.org](https://profiles.wordpress.org/sureshsornapudi09/)

## License

GPL-2.0-or-later. See [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html).
