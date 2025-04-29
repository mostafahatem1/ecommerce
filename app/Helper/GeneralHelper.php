<?php

use App\Models\Permission;




function isActiveMenu($menu, $current_route)
{   // admin.product_categories.create
    // Check 1: 'admin.product_categories.create' === 'admin.product_categories.index' → false
    // Check 2: Has children? Yes → proceed to recursion

    //     isActiveMenu(parent)
    // ├─ Check parent → false
    // ├─ isActiveMenu(child1)
    // │  ├─ Check child1 → true ← MATCH FOUND
    // ├─ Returns true
    // When any child is active, it makes the parent active through recursion

    $menuRoute = 'admin.' . $menu->as;

    // Exact match
    if ($current_route === $menuRoute) {
        return true;
    }

    // Parent match (for when parent should be active for child routes)
    if ($menu->relationLoaded('children')) {
        foreach ($menu->children as $child) {
            if (isActiveMenu($child, $current_route)) {
                return true;
            }
        }
    }

    return false;
}

function createPermissionGroup($module, $icon = null, $ordering = 0, $customNames = [])
{
    // Ensure module name is formatted correctly (e.g., "product categories" => "product_categories")
    $module_slug = str_replace(' ', '_', strtolower($module));

    // Create main permission (group manager)
    $manage = Permission::create([
        'name' => 'manage_' . $module_slug,
        'display_name' => ucfirst($module),
        'route' => $module_slug,
        'module' => $module_slug,
        'as' => $module_slug . '.index',
        'icon' => $icon,
        'parent' => 0,
        'parent_original' => 0,
        'sidebar_link' => 1,
        'appear' => 1,
        'ordering' => $ordering,
    ]);

    $manage->parent_show = $manage->id;
    $manage->save();

    $children = [
        'show' => ['display_name' => ucfirst($module), 'as' => $module_slug . '.index', 'appear' => 1],
        'create' => ['display_name' => 'Create ' . ucfirst(rtrim($module, 's')), 'as' => $module_slug . '.create'],
        'display' => ['display_name' => 'Show ' . ucfirst(rtrim($module, 's')), 'as' => $module_slug . '.show'],
        'update' => ['display_name' => 'Update ' . ucfirst(rtrim($module, 's')), 'as' => $module_slug . '.edit'],
        'delete' => ['display_name' => 'Delete ' . ucfirst(rtrim($module, 's')), 'as' => $module_slug . '.destroy'],
    ];

    foreach ($children as $key => $perm) {
        $name = $key . '_' . $module_slug;
        $data = array_merge([
            'name' => $name,
            'route' => $module_slug,
            'module' => $module_slug,
            'icon' => null,
            'parent' => $manage->id,
            'parent_original' => $manage->id,
            'parent_show' => $manage->id,
            'sidebar_link' => 1,
            'appear' => $perm['appear'] ?? 0,
        ], $perm);

        Permission::create($data);
    }
}




 function getNumbers()
 {
     $subtotal = Cart::instance('default')->subtotal();
     $discount = session()->has('coupon') ? session()->get('coupon')['discount'] : 0.00;
     $discount_code = session()->has('coupon') ? session()->get('coupon')['code'] : null;

     $subtotal_after_discount = $subtotal - $discount;

     $tax = config('cart.tax') / 100;
     $taxText = config('cart.tax') . '%';

     $productTaxes = round($subtotal_after_discount * $tax, 2);
     $newSubTotal = $subtotal_after_discount + $productTaxes;

     $shipping = session()->has('shipping') ? session()->get('shipping')['cost'] : 0.00;
     $shipping_code = session()->has('shipping') ? session()->get('shipping')['code'] : null;

     $total = ($newSubTotal + $shipping) > 0 ? round($newSubTotal + $shipping, 2) : 0.00;

     return collect([
         'subtotal' => $subtotal,
         'tax' => $productTaxes,
         'taxText' => $taxText,
         'productTaxes' => (float)$productTaxes,
         'newSubTotal' => (float)$newSubTotal,
         'discount' => (float)$discount,
         'discount_code' => $discount_code,
         'shipping' => (float)$shipping,
         'shipping_code' => $shipping_code,
         'total' => (float)$total,
     ]);
 }
