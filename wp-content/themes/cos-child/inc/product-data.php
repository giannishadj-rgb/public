<?php
/**
 * COS Child Theme — Product copy data provider
 *
 * Single source of truth for the editorial content single-product.php
 * renders per SKU (subtitle, benefits, who-it's-for, skin concerns, texture,
 * specs, FAQs, actives, how-to-apply, clinical stats, INCI). Previously this
 * was duplicated across two separate arrays in the template itself
 * ($_rich_early and $rich), with the second silently overwriting the first
 * and — because it had no COS-BUNDLE entry — erasing the bundle's data
 * entirely partway through the page. This file fixes both problems: one
 * array, and COS-BUNDLE keeps its (smaller) key set on purpose rather than
 * disappearing.
 *
 * `stats` entries are only included here when the Clinical Studies page
 * (/science/clinical-studies/) discloses the methodology (n-size, age range,
 * duration, method) behind that specific number — see OWNER-ACTIONS.md for
 * the claims register. Numbers that had no disclosed backing (e.g. COS-001's
 * "99.8% / 0%", COS-002's "+38% / 94%", COS-003's "−29% / 91%", COS-004's
 * "+52% / −24%") were removed rather than kept for visual symmetry; the
 * corresponding FAQ/prose sentences that cited them were reworded to stop
 * short of a specific unsubstantiated number.
 */

defined( 'ABSPATH' ) || exit;

function cos_get_product_copy( string $sku ): ?array {
	$data = array(
		'COS-001' => array(
			'subtitle' => 'Cleanser · Make-up Remover · Toner',
			'image_alt' => 'Micellar Water All-in-One Cleanser with Caviar Extract — luxury micellar water cleanser bottle',
			'actives_h2' => 'Inside this luxury micellar water cleanser.',
			'benefits' => array(
				'Gently removes make-up and daily impurities',
				'Cleanses, tones, and refreshes in one step',
				'Helps protect the skin against moisture loss',
				'Helps reduce the feeling of redness, tightness, and dryness',
				'Leaves skin feeling clean, soft, and supple',
				'Prepares the skin perfectly for serum and moisturiser',
			),
			'who_for'  => 'Formulated to be gentle for most skin types, including sensitive skin — we recommend a patch test if you have specific concerns. Ideal for anyone who wants a no-rinse Step 1 that removes make-up and prepares skin for Advanced Repair Serum.',
			'concerns' => array( 'Daily impurities', 'Make-up removal', 'Sensitive-feeling skin', 'Dryness & tightness', 'Loss of freshness' ),
			'texture'  => 'A fresh, lightweight cleansing water that glides across the skin without heaviness, rinsing, or residue. The skin is left with a clean, comfortable, refreshed sensation.',
			'specs'    => array(
				array( 'Volume', '150 ml' ),
				array( 'Product type', 'Cleanser · Make-up Remover · Toner' ),
				array( 'Origin', 'Developed and Made in Switzerland' ),
			),
			'faqs' => array(
				array( 'Is rinsing required?', 'No. Simply sweep across the skin with a cotton pad and leave — no rinsing needed. The formula can be emulsified with water and rinsed if preferred.' ),
				array( 'Can I use it to remove eye make-up?', 'Yes. Hold a soaked cotton pad gently over the closed eye for a few seconds, then wipe downwards and outwards without rubbing.' ),
				array( 'Is it suitable for sensitive skin?', 'The formula is designed to be gentle for most skin types, including sensitive and reactive skin. We recommend a patch test before first use if you have specific concerns.' ),
				array( 'When should I use it in my routine?', 'Use morning and evening as Step 1 of the Caviar of Switzerland ritual, before applying Advanced Repair Serum and 24h Regeneration Cream.' ),
				array( 'What is the Caviar of Switzerland High-Tech Complex?', 'The High-Tech Complex is the signature active blend of Caviar Extract, SYN®-COLL, Hyaluronic Acid, Superoxide Dismutase, and Resveratrol — chosen to help support hydration, visible firmness, antioxidant care, and a more youthful-looking complexion.' ),
				array( 'How long will one bottle last?', 'With twice-daily use at the recommended amount, one 150 ml bottle lasts approximately 8&ndash;10 weeks.' ),
				array( 'Why does this cleanser cost more than a typical micellar water?', 'The price reflects Swiss development and manufacture, and a formula built around Caviar Extract and a dedicated micellar complex rather than a generic surfactant base — chosen to remove impurities without compromising the skin barrier the following steps depend on.' ),
			),
			'actives' => array(
				array( 'Caviar Extract', 'Micro-nutrient complex from Oscietra caviar — replenishes skin lipids and trace minerals depleted during cleansing.' ),
				array( 'Micellar Complex', 'Oil-in-water system that magnetically binds to make-up, SPF, and pollution particles without disrupting the moisture barrier.' ),
				array( 'Panthenol (Vitamin B5)', 'Draws moisture into freshly cleansed skin and soothes any sensitivity triggered by environmental exposure.' ),
				array( 'Allantoin', 'Promotes surface cell renewal and eliminates the tightness that follows conventional cleansers.' ),
			),
			'how' => array(
				'Soak a cotton pad with Micellar Water — morning and evening.',
				'Sweep gently across the face and neck in upward motions.',
				'For eye make-up: hold the pad over the closed eye for a few seconds, then wipe downwards and outwards. Do not rub.',
				'No rinsing required. Follow immediately with Advanced Repair Serum.',
			),
			// No stats entry: none of this product's original figures (99.8% /
			// 0% / 28 days) are disclosed with methodology anywhere on the site.
			'inci' => 'Aqua, Hexylene Glycol, Glycerin, Poloxamer 184, Disodium Cocoamphodiacetate, Panthenol, Allantoin, Acipenser Gueldenstaedtii Roe Extract, Chamomilla Recutita Flower Extract, Sodium Hyaluronate, Phenoxyethanol, Ethylhexylglycerin, Citric Acid, Sodium Citrate.',
		),

		'COS-002' => array(
			'subtitle' => 'The Flagship Serum',
			'image_alt' => 'Advanced Repair Serum with Caviar Extract — firming anti-aging face serum bottle',
			'actives_h2' => 'Inside this firming anti-aging face serum.',
			'benefits' => array(
				'Helps improve the appearance of stressed and tired-looking skin',
				'Supports a firmer, smoother-looking complexion',
				'Helps soften the appearance of fine lines and wrinkles',
				'Provides antioxidant care against environmental stressors',
				'Helps restore the look of radiance and vitality',
				'Lightweight, silky, and fast absorbing',
			),
			'who_for'  => 'Ideal for skin showing dullness, dryness, loss of firmness, visible fatigue, fine lines, or signs of environmental stress.',
			'concerns' => array( 'Fine lines & wrinkles', 'Dullness', 'Loss of firmness', 'Environmental stress', 'Dehydrated-looking skin', 'Uneven skin vitality' ),
			'texture'  => 'A fine, lightweight serum with a silky, non-oily finish. It absorbs quickly so 24h Regeneration Cream can be applied as the sealing step without heaviness or residue.',
			'specs'    => array(
				array( 'Volume', '30 ml' ),
				array( 'Product type', 'Face serum' ),
				array( 'Origin', 'Developed and Made in Switzerland' ),
			),
			'faqs' => array(
				array( 'Can I use it morning and evening?', 'Yes. Apply morning and evening to thoroughly cleansed skin, then follow with 24h Regeneration Cream.' ),
				array( 'Do I apply it before or after moisturiser?', 'Always apply serum before moisturiser. The lightweight texture absorbs fully before the cream seals in the actives.' ),
				array( 'When will I notice results?', 'In our independent clinical study, cutometer-measured skin firmness improved by +47% at 28 days — see the full methodology on our Clinical Studies page. Panellists also reported visibly smoother-looking texture.' ),
				array( 'How much should I use?', 'Dispense 2–3 drops into the palm and press gently across the face and neck. A small amount goes a long way.' ),
				array( 'What is the Caviar of Switzerland High-Tech Complex?', 'The High-Tech Complex is the signature active blend of Caviar Extract, SYN®-COLL, Hyaluronic Acid, Superoxide Dismutase, and Resveratrol — chosen to help support hydration, visible firmness, antioxidant care, and a more youthful-looking complexion.' ),
				array( 'How long will one bottle last?', 'With twice-daily use at the recommended amount, one 30 ml bottle lasts approximately 6&ndash;8 weeks.' ),
				array( 'Why does this serum cost what it does?', 'The price reflects the concentration of clinically-tested actives — including micro-encapsulated caviar peptides and SYN®-COLL Peptide — and the independent 28-day study behind the +47% firmness result, not the packaging or the brand name alone.' ),
			),
			'actives' => array(
				array( 'Micro-Encapsulated Oscietra Caviar', 'Time-release delivery technology ensures active caviar peptides penetrate into the dermis rather than remaining at the surface.' ),
				array( 'SYN®-COLL Peptide', 'Clinically validated tripeptide that directly stimulates the skin\'s own collagen synthesis pathway. +47% perceived firmness at 28 days.' ),
				array( 'Low-MW Sodium Hyaluronate', 'Ultra-small molecular weight allows penetration below the epidermis, binding 1,000× its weight in water at the dermal level.' ),
				array( 'Resveratrol', 'Potent antioxidant extracted from grape skin that neutralises free radicals and supports cellular longevity.' ),
			),
			'how' => array(
				'Apply to cleansed skin — after Step 01 Micellar Water.',
				'Dispense 2–3 drops into palm and press gently across face and neck.',
				'Allow 60 seconds to absorb fully before the next step.',
				'Use morning and evening for optimal results.',
			),
			'stats' => array(
				array( '+47%', 'Perceived firmness increase at 28 days' ),
			),
			'inci' => 'Aqua, Glycerin, Niacinamide, Butylene Glycol, Acipenser Gueldenstaedtii Roe Extract (Micro-Encapsulated), Tripeptide-5 (SYN®-COLL), Sodium Hyaluronate, Resveratrol, Tocopheryl Acetate, Xanthan Gum, Carbomer, Sodium Hydroxide, Phenoxyethanol, Ethylhexylglycerin.',
		),

		'COS-003' => array(
			'subtitle' => 'Precision Eye Contour Care',
			'image_alt' => 'Revitalizing Eye Cream with Caviar Extract — anti-aging eye cream for dark circles and fine lines',
			'actives_h2' => 'Inside this anti-aging eye cream for dark circles.',
			'benefits' => array(
				'Helps smooth the appearance of fine lines and wrinkles',
				'Helps improve the look of firmness around the eyes',
				'Helps diminish the appearance of dark circles',
				'Hydrates and nourishes delicate eye-area skin',
				'Leaves the eye contour looking fresher, smoother, and more youthful',
			),
			'who_for'  => 'Ideal for dryness, fine lines, wrinkles, dark circles, and visible tiredness around the eyes.',
			'concerns' => array( 'Fine lines around the eyes', 'Wrinkles', 'Dark circles', 'Loss of firmness', 'Dryness', 'Visible fatigue' ),
			'texture'  => 'A rich, comforting cream calibrated for the delicate eye area. It melts on contact, leaving the skin feeling hydrated, smooth, and softly replenished without any heaviness.',
			'specs'    => array(
				array( 'Volume', '15 ml' ),
				array( 'Product type', 'Eye cream' ),
				array( 'Origin', 'Developed and Made in Switzerland' ),
			),
			'faqs' => array(
				array( 'How do I apply eye cream correctly?', 'Using your ring finger, apply a grain-of-rice amount and tap gently along the orbital bone from inner to outer corner. Never drag or rub the delicate skin around the eyes.' ),
				array( 'When in my routine do I apply it?', 'After cleansing and serum, before or after moisturiser according to your preference.' ),
				array( 'Can I wear it under make-up?', 'Yes. Allow a moment to absorb before applying make-up or SPF.' ),
				array( 'How long before I see results for dark circles?', 'In our independent consumer panel study, most panellists reported visibly reduced dark circle intensity by 28 days.' ),
				array( 'What is the Caviar of Switzerland High-Tech Complex?', 'The High-Tech Complex is the signature active blend of Caviar Extract, SYN®-COLL, Hyaluronic Acid, Superoxide Dismutase, and Resveratrol — chosen to help support hydration, visible firmness, antioxidant care, and a more youthful-looking complexion.' ),
				array( 'How long will one jar last?', 'With twice-daily use at the recommended amount, one 15 ml jar lasts approximately 6&ndash;8 weeks.' ),
				array( 'Why does this eye cream cost more than a general face cream?', 'The price reflects a formula engineered specifically for the periorbital area — precision-encapsulated actives, Vitamin K2, and triple-weight hyaluronic acid — rather than a scaled-down version of the face serum, plus the independent study behind its −38% fine-line-depth result.' ),
			),
			'actives' => array(
				array( 'Micro-Encapsulated Caviar Actives', 'Precision encapsulation delivers actives to the periorbital zone — the thinnest, most delicate skin on the body — without irritation.' ),
				array( 'Caffeine', 'Constricts blood vessels to visibly reduce morning puffiness and neutralises free radicals that accelerate fine line formation around the eyes.' ),
				array( 'Vitamin K2 (Menaquinone-7)', 'Targets haemoglobin deposits beneath the orbital skin — clinically linked to measurable reduction of dark circle intensity.' ),
				array( 'Triple-Weight Hyaluronic Acid', 'Three molecular sizes work simultaneously — surface plumping, mid-layer hydration, and deep moisture reserve in the periorbital dermis.' ),
			),
			'how' => array(
				'Apply after serum, before moisturiser — Step 03 of the ritual.',
				'Dispense a grain-of-rice amount onto the ring finger per eye.',
				'Pat gently along the orbital bone from inner to outer corner. Never drag.',
				'Use morning and evening. Compatible with SPF and make-up.',
			),
			'stats' => array(
				array( '−38%', 'Fine line depth reduction at 28 days' ),
			),
			'inci' => 'Aqua, Glycerin, Caffeine, Butylene Glycol, Acipenser Gueldenstaedtii Roe Extract (Micro-Encapsulated), Sodium Hyaluronate (Triple Weight), Menaquinone-7, Palmitoyl Pentapeptide-4, Xanthan Gum, Carbomer, Triethanolamine, Phenoxyethanol, Ethylhexylglycerin.',
		),

		'COS-004' => array(
			'subtitle' => 'Day-and-Night Caviar Moisturiser',
			'image_alt' => '24h Regeneration Cream with Caviar Extract — 24-hour regenerating face cream jar',
			'actives_h2' => 'Inside this 24-hour regenerating face cream.',
			'benefits' => array(
				'Provides lasting hydration and comfort',
				'Helps reduce the appearance of fine lines and wrinkles',
				'Helps protect against environmental stressors',
				'Supports a firmer, more elastic-looking complexion',
				'Helps prevent dryness and dehydration',
				'Leaves skin looking more vibrant, smooth, and radiant',
			),
			'who_for'  => 'Ideal for dry, dehydrated, mature, or stressed skin, and for anyone seeking a daily moisturiser powered by the Caviar of Switzerland High-Tech Complex.',
			'concerns' => array( 'Dryness', 'Dehydration', 'Fine lines & wrinkles', 'Loss of elasticity', 'Dullness', 'Environmental stress' ),
			'texture'  => 'A concentrated yet lightweight silky cream that melts into the skin on application. It seals in Advanced Repair Serum while leaving the complexion soft, smooth, and comfortable — never heavy.',
			'specs'    => array(
				array( 'Volume', '50 ml' ),
				array( 'Product type', 'Face moisturiser' ),
				array( 'Origin', 'Developed and Made in Switzerland' ),
			),
			'faqs' => array(
				array( 'Can I use it both morning and evening?', 'Yes. The formula is designed for both day and night use — rich enough for deep overnight regeneration, light enough to wear comfortably beneath SPF.' ),
				array( 'Do I need SPF on top in the morning?', 'Yes. We recommend applying a broad-spectrum SPF 50+ over the cream during daylight hours for complete environmental protection.' ),
				array( 'Can I use it without the serum?', 'The cream works beautifully on its own. For the complete ritual and maximum results, we recommend applying it after Advanced Repair Serum.' ),
				array( 'How long does the hydration last?', 'The cream is formulated for extended hydration; in our independent consumer panel study, 100% of panellists reported satisfaction at 28 days — see our Clinical Studies page for the full results.' ),
				array( 'What is the Caviar of Switzerland High-Tech Complex?', 'The High-Tech Complex is the signature active blend of Caviar Extract, SYN®-COLL, Hyaluronic Acid, Superoxide Dismutase, and Resveratrol — chosen to help support hydration, visible firmness, antioxidant care, and a more youthful-looking complexion.' ),
				array( 'How long will one jar last?', 'With twice-daily use at the recommended amount, one 50 ml jar lasts approximately 6&ndash;8 weeks.' ),
				array( 'Why does this cream cost what it does?', 'The price reflects Swiss Glacier Meltwater as the formula\'s base, a triple-weight hyaluronic acid complex, and Superoxide Dismutase at a concentration intended for both day and overnight use — evidenced by the independent panel study behind its 100% satisfaction result.' ),
			),
			'actives' => array(
				array( 'Caviar Lipid Complex', 'Mirrors the skin\'s natural lipid matrix — replenishes the barrier depleted by environmental exposure and the active phases of the ritual.' ),
				array( 'Triple-Weight Hyaluronic Acid Complex', 'Three molecular weights working simultaneously: surface plumping, mid-dermal hydration, and a deep moisture reservoir designed for extended hydration.' ),
				array( 'Swiss Glacier Meltwater', 'Ultra-pure Alpine water with elevated silica and mineral content. The base of every Caviar of Switzerland formula.' ),
				array( 'Superoxide Dismutase', 'Enzymatic antioxidant that neutralises superoxide free radicals — the most reactive and damaging generated by UV and pollution exposure.' ),
			),
			'how' => array(
				'The final step — apply after serum and eye cream.',
				'Warm a pea-sized amount between palms for 3 seconds.',
				'Press firmly across face and neck to seal in the ritual\'s active layers.',
				'Morning: wear under SPF. Evening: use as an overnight regeneration treatment.',
			),
			'stats' => array(
				array( '100%', 'Panellist satisfaction at 28 days' ),
			),
			'inci' => 'Aqua (Swiss Glacier Meltwater), Glycerin, Dimethicone, Cetearyl Alcohol, Acipenser Gueldenstaedtii Roe Extract, Sodium Hyaluronate (Triple Weight Complex), Superoxide Dismutase, Tocopheryl Acetate, Ceteareth-20, Stearic Acid, Glyceryl Stearate, Carbomer, Sodium Hydroxide, Phenoxyethanol, Ethylhexylglycerin.',
		),

		'COS-BUNDLE' => array(
			'subtitle' => 'The Complete Four-Step System',
			'image_alt' => 'The Complete Ritual — luxury anti-aging skincare set, complete four-step routine',
			'benefits' => array(
				'All four ritual steps, formulated to work in sequence',
				'Each step prepares the skin for the one that follows',
				'Built around the shared Caviar of Switzerland High-Tech Complex',
				'Save €50 versus buying each product individually',
				'Presented in the Caviar of Switzerland ritual box — ready to gift',
				'Includes a written ritual guide and complimentary tracked worldwide delivery',
			),
			// Bundle deliberately has only these 4 keys — it has no per-SKU
			// specs/faqs/actives/how/stats/inci of its own (each component
			// product's page covers that); see the "Combined Clinical Results"
			// and "Bundle FAQ" sections in single-product.php instead.
			'who_for'  => 'For anyone ready to adopt the complete four-step ritual rather than building it product by product. Formulated to be gentle for most skin types, including sensitive skin, and covering concerns spanning daily cleansing and make-up removal, dullness and loss of firmness, fine lines and dark circles around the eyes, and dryness, dehydration, or environmental stress that a single product can\'t address on its own.',
			'concerns' => array( 'Daily impurities', 'Fine lines & wrinkles', 'Dark circles', 'Loss of firmness', 'Dullness', 'Dryness', 'Environmental stress', 'Visible fatigue' ),
		),
	);

	return $data[ $sku ] ?? null;
}
