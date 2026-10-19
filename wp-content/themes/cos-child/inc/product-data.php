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
			'image_alt' => 'Micellar Water All-in-One Cleanser by Caviar of Switzerland held upright.',
			'summary'  => 'A no-rinse micellar water that removes make-up and daily impurities: the first gesture of the four-step ritual.',
			'description' => '<h2>The First Gesture</h2><p>Step 1 of the ritual: a precise, no-rinse cleanse with Caviar Extract, Aloe Vera, Tamarind Seed Extract and a Skin Barrier Optimizer that prepares the skin for Advanced Repair Serum.</p><p>A fresh, lightweight cleansing water that glides across the skin without heaviness, rinsing or residue, leaving it feeling clean, soft and comfortable.</p>',
			'description_image' => array(
				'dir'     => 'pdp/cleanser-ritual',
				'desktop' => array( 'widths' => array( 560, 840, 1120 ), 'w' => 1120, 'h' => 1400 ),
				'mobile'  => array( 'widths' => array( 720, 1080 ), 'w' => 1080, 'h' => 1080 ),
				'alt'     => 'Model reclining beside an upright bottle of Micellar Water All-in-One Cleanser.',
			),
			'actives_h2' => 'Inside this luxury micellar water cleanser.',
			'benefits' => array(
				'Gently removes make-up and daily impurities',
				'Cleanses, tones, and refreshes in one step',
				'Helps protect the skin against moisture loss',
			),
			'who_for'  => 'Formulated to be gentle for most skin types, including sensitive skin — we recommend a patch test if you have specific concerns.',
			'concerns' => array( 'Daily impurities', 'Make-up removal', 'Sensitive-feeling skin', 'Dryness & tightness', 'Loss of freshness' ),
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
				array( 'Why does this cleanser cost more than a typical micellar water?', 'The price reflects Swiss development and manufacture, and a formula built around Caviar Extract and a dedicated micellar complex rather than a generic surfactant base — chosen to remove impurities without compromising the skin barrier the following steps depend on.' ),
			),
			'actives' => array(
				array( 'Caviar Extract', 'From the caviar of sustainably farmed Siberian sturgeon (Acipenser baerii): helps replenish the lipids and minerals that cleansing can remove.' ),
				array( 'Micellar Complex', 'Tiny micelles that gather make-up, SPF and the day’s particles without disturbing the skin’s moisture barrier.' ),
				array( 'Panthenol (Vitamin B5)', 'Draws moisture into freshly cleansed skin and helps it feel soft and comfortable.' ),
				array( 'Allantoin', 'Helps soothe the skin and ease the tightness conventional cleansers can leave behind.' ),
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
			'image_alt' => 'Advanced Repair Serum by Caviar of Switzerland displayed upright.',
			// PDP audit 2026-09-27: each section below has one job, and no
			// concern/benefit wording is repeated across them. 'summary'
			// replaces the WooCommerce short description on the PDP only.
			'summary'  => 'A lightweight, concentrated firming serum at the heart of the four-step ritual.',
			'description' => '<h2>The Heart of the Ritual</h2><p>Step 2 of the ritual, between Micellar Water and 24h Regeneration Cream: a fine, lightweight serum that concentrates the High-Tech Complex.</p><p>Its silky, non-oily texture absorbs quickly, so the cream that follows seals the routine without heaviness or residue.</p>',
			'description_image' => array(
				'dir'     => 'pdp/serum-ritual',
				'desktop' => array( 'widths' => array( 560, 840, 1120 ), 'w' => 1120, 'h' => 1400 ),
				'mobile'  => array( 'widths' => array( 720, 1080 ), 'w' => 1080, 'h' => 1080 ),
				'alt'     => 'Model resting her hands beside an upright bottle of Advanced Repair Serum.',
			),
			'editorial' => array(
				'dir'     => 'pdp/serum-application',
				'desktop' => array( 'widths' => array( 600, 800, 1200 ), 'w' => 1200, 'h' => 1200 ),
				'mobile'  => array( 'widths' => array( 720, 1080 ), 'w' => 1080, 'h' => 1080 ),
				'alt'     => 'Model dispensing Advanced Repair Serum onto the back of her hand.',
			),
			'actives_h2' => 'Inside this firming anti-aging face serum.',
			'benefits' => array(
				'Helps improve the appearance of stressed and tired-looking skin',
				'Supports a firmer, smoother-looking complexion',
				'Helps soften the appearance of fine lines and wrinkles',
			),
			'who_for'  => 'Chosen for skin showing one or more of these concerns.',
			'concerns' => array( 'Fine lines & wrinkles', 'Dullness', 'Loss of firmness', 'Environmental stress', 'Dehydrated-looking skin', 'Uneven skin vitality' ),
			'specs'    => array(
				array( 'Volume', '30 ml' ),
				array( 'Product type', 'Face serum' ),
				array( 'Origin', 'Developed and Made in Switzerland' ),
			),
			'faqs' => array(
				array( 'Can I use it morning and evening?', 'Yes. Apply morning and evening to thoroughly cleansed skin, then follow with 24h Regeneration Cream.' ),
				array( 'Do I apply it before or after moisturiser?', 'Always apply serum before moisturiser. The lightweight texture absorbs fully before the cream seals in the actives.' ),
				array( 'When will I notice results?', 'In an independent 28-day study of 32 women aged 45–60, skin firmness improved by 47% on average, measured by cutometry. The full methodology is on our Clinical Studies page.' ),
				array( 'How much should I use?', 'Dispense 2–3 drops into the palm and press gently across the face and neck. A small amount goes a long way.' ),
				array( 'What is the Caviar of Switzerland High-Tech Complex?', 'The High-Tech Complex is the signature active blend of Caviar Extract, SYN®-COLL, Hyaluronic Acid, Superoxide Dismutase, and Resveratrol — chosen to help support hydration, visible firmness, antioxidant care, and a more youthful-looking complexion.' ),
				array( 'Why does this serum cost what it does?', 'The price reflects the concentration of its actives — including micro-encapsulated caviar extract and SYN®-COLL peptide — and the independent 28-day study behind its +47% firmness result, not the packaging or the brand name alone.' ),
			),
			'actives' => array(
				array( 'Micro-Encapsulated Siberian Sturgeon Caviar', 'Time-release micro-encapsulation protects the caviar extract’s potency until the moment it meets the skin.' ),
				array( 'SYN®-COLL Peptide', 'A tripeptide studied in collagen research, chosen for skin that looks firmer and smoother.' ),
				array( 'Low-MW Sodium Hyaluronate', 'A low molecular weight hyaluronic acid that holds moisture, for skin that feels supple and looks smoother.' ),
				array( 'Resveratrol', 'An antioxidant from grape skin that helps defend the skin against everyday environmental stress.' ),
			),
			'how' => array(
				'Apply to cleansed skin — after Step 01 Micellar Water.',
				'Dispense 2–3 drops into palm and press gently across face and neck.',
				'Allow 60 seconds to absorb fully before the next step.',
				'Use morning and evening for optimal results.',
			),
			'stats' => array(
				array( '+47%', 'Skin firmness, instrument-measured (cutometry) at 28 days' ),
			),
			'inci' => 'Aqua, Glycerin, Niacinamide, Butylene Glycol, Acipenser Gueldenstaedtii Roe Extract (Micro-Encapsulated), Tripeptide-5 (SYN®-COLL), Sodium Hyaluronate, Resveratrol, Tocopheryl Acetate, Xanthan Gum, Carbomer, Sodium Hydroxide, Phenoxyethanol, Ethylhexylglycerin.',
		),

		'COS-003' => array(
			'subtitle' => 'Precision Eye Contour Care',
			'image_alt' => 'Revitalizing Eye Cream by Caviar of Switzerland held upright.',
			'summary'  => 'A rich yet elegant eye cream for fine lines and dark circles: Step 3 of the four-step ritual.',
			'description' => '<h2>The Detail That Changes Everything</h2><p>Step 3 of the ritual, after the serum and before 24h Regeneration Cream: the High-Tech Complex with Vitamin E, Provitamin B5 and Organic Floral Water, in a cream calibrated for the delicate eye contour.</p><p>It melts on contact, leaving the skin feeling hydrated, smooth and softly replenished without any heaviness.</p>',
			'description_image' => array(
				'dir'     => 'pdp/eye-ritual',
				'desktop' => array( 'widths' => array( 560, 840, 1120 ), 'w' => 1120, 'h' => 1400 ),
				'mobile'  => array( 'widths' => array( 720, 1080 ), 'w' => 1080, 'h' => 1080 ),
				'alt'     => 'Revitalizing Eye Cream held upright between two models.',
			),
			'editorial' => array(
				'dir'     => 'pdp/eye-application',
				'desktop' => array( 'widths' => array( 600, 800, 1200 ), 'w' => 1200, 'h' => 1200 ),
				'mobile'  => array( 'widths' => array( 720, 1080 ), 'w' => 1080, 'h' => 1080 ),
				'alt'     => 'Model holding Revitalizing Eye Cream close to the eye contour.',
			),
			'actives_h2' => 'Inside this anti-aging eye cream for dark circles.',
			'benefits' => array(
				'Helps smooth the appearance of fine lines and wrinkles',
				'Helps improve the look of firmness around the eyes',
				'Helps diminish the appearance of dark circles',
			),
			'who_for'  => 'Chosen for an eye contour showing one or more of these concerns.',
			'concerns' => array( 'Fine lines around the eyes', 'Wrinkles', 'Dark circles', 'Loss of firmness', 'Dryness', 'Visible fatigue' ),
			'specs'    => array(
				array( 'Volume', '15 ml' ),
				array( 'Product type', 'Eye cream' ),
				array( 'Origin', 'Developed and Made in Switzerland' ),
			),
			'faqs' => array(
				array( 'How do I apply eye cream correctly?', 'Using your ring finger, apply a grain-of-rice amount and tap gently along the orbital bone from inner to outer corner. Never drag or rub the delicate skin around the eyes.' ),
				array( 'When in my routine do I apply it?', 'After cleansing and serum, before or after moisturiser according to your preference.' ),
				array( 'Can I wear it under make-up?', 'Yes. Allow a moment to absorb before applying make-up or SPF.' ),
				array( 'How long before I see results for dark circles?', 'Results vary from person to person. In an independent 28-day study, crow’s-feet depth was reduced by 38% on average, measured by optical profilometry; dark circles were not instrument-measured. The full methodology is on our Clinical Studies page.' ),
				array( 'What is the Caviar of Switzerland High-Tech Complex?', 'The High-Tech Complex is the signature active blend of Caviar Extract, SYN®-COLL, Hyaluronic Acid, Superoxide Dismutase, and Resveratrol — chosen to help support hydration, visible firmness, antioxidant care, and a more youthful-looking complexion.' ),
				array( 'Why does this eye cream cost more than a general face cream?', 'The price reflects a formula engineered specifically for the periorbital area — precision-encapsulated actives, Vitamin K2, and triple-weight hyaluronic acid — rather than a scaled-down version of the face serum, plus the independent study behind its −38% crow’s-feet depth result.' ),
			),
			'actives' => array(
				array( 'Micro-Encapsulated Caviar Actives', 'Precision encapsulation, calibrated for the eye contour: the thinnest, most delicate skin of the face.' ),
				array( 'Caffeine', 'Helps visibly reduce the look of morning puffiness, with antioxidant care for the eye contour.' ),
				array( 'Vitamin K2 (Menaquinone-7)', 'Chosen to help diminish the appearance of dark circles beneath the delicate eye contour.' ),
				array( 'Triple-Weight Hyaluronic Acid', 'Three molecular sizes of hyaluronic acid, for hydration that feels immediate and lasts.' ),
			),
			'how' => array(
				'Apply after serum, before moisturiser — Step 03 of the ritual.',
				'Dispense a grain-of-rice amount onto the ring finger per eye.',
				'Pat gently along the orbital bone from inner to outer corner. Never drag.',
				'Use morning and evening. Compatible with SPF and make-up.',
			),
			'stats' => array(
				array( '−38%', 'Crow’s-feet depth, instrument-measured (optical profilometry) at 28 days' ),
			),
			'inci' => 'Aqua, Glycerin, Caffeine, Butylene Glycol, Acipenser Gueldenstaedtii Roe Extract (Micro-Encapsulated), Sodium Hyaluronate (Triple Weight), Menaquinone-7, Palmitoyl Pentapeptide-4, Xanthan Gum, Carbomer, Triethanolamine, Phenoxyethanol, Ethylhexylglycerin.',
		),

		'COS-004' => array(
			'subtitle' => 'Day-and-Night Caviar Moisturiser',
			'image_alt' => '24h Regeneration Cream by Caviar of Switzerland, label facing forward.',
			'summary'  => 'A silky day-and-night cream that seals in moisture: the final step of the four-step ritual.',
			'description' => '<h2>The Completion</h2><p>Step 4 of the ritual, morning and evening: the High-Tech Complex with Vitamin E, Provitamin B5 and Shea Butter, sealing in Advanced Repair Serum.</p><p>A concentrated yet lightweight silky cream that melts into the skin, leaving the complexion soft, smooth and comfortable, never heavy.</p>',
			'description_image' => array(
				'dir'     => 'pdp/cream-ritual',
				'desktop' => array( 'widths' => array( 560, 840, 1120 ), 'w' => 1120, 'h' => 1400 ),
				'mobile'  => array( 'widths' => array( 720, 1080 ), 'w' => 1080, 'h' => 1080 ),
				'alt'     => 'Model resting her arms behind a jar of 24h Regeneration Cream.',
			),
			'editorial' => array(
				'dir'     => 'pdp/cream-application',
				'desktop' => array( 'widths' => array( 600, 800, 1200 ), 'w' => 1200, 'h' => 1200 ),
				'mobile'  => array( 'widths' => array( 720, 1080 ), 'w' => 1080, 'h' => 1080 ),
				'alt'     => 'Fingers taking 24h Regeneration Cream from the jar, with texture on the hand.',
			),
			'actives_h2' => 'Inside this 24-hour regenerating face cream.',
			'benefits' => array(
				'Provides lasting hydration and comfort',
				'Helps reduce the appearance of fine lines and wrinkles',
				'Helps protect against environmental stressors',
			),
			'who_for'  => 'Chosen for skin showing one or more of these concerns.',
			'concerns' => array( 'Dryness', 'Dehydration', 'Fine lines & wrinkles', 'Loss of elasticity', 'Dullness', 'Environmental stress' ),
			'specs'    => array(
				array( 'Volume', '50 ml' ),
				array( 'Product type', 'Face moisturiser' ),
				array( 'Origin', 'Developed and Made in Switzerland' ),
			),
			'faqs' => array(
				array( 'Can I use it both morning and evening?', 'Yes. The formula is designed for both day and night use — rich enough for the night, light enough to wear comfortably beneath SPF.' ),
				array( 'Do I need SPF on top in the morning?', 'Yes. We recommend applying a broad-spectrum SPF 50+ over the cream during daylight hours for complete environmental protection.' ),
				array( 'Can I use it without the serum?', 'The cream works beautifully on its own. For the complete ritual and maximum results, we recommend applying it after Advanced Repair Serum.' ),
				array( 'How long does the hydration last?', 'The cream is formulated for lasting hydration and comfort through the day, with a triple-weight hyaluronic acid complex and a caviar lipid complex that supports the skin\'s barrier.' ),
				array( 'What is the Caviar of Switzerland High-Tech Complex?', 'The High-Tech Complex is the signature active blend of Caviar Extract, SYN®-COLL, Hyaluronic Acid, Superoxide Dismutase, and Resveratrol — chosen to help support hydration, visible firmness, antioxidant care, and a more youthful-looking complexion.' ),
				array( 'Why does this cream cost what it does?', 'The price reflects Swiss glacier meltwater as the formula\'s base, a triple-weight hyaluronic acid complex, and Superoxide Dismutase at a concentration intended for both day and night use.' ),
			),
			'actives' => array(
				array( 'Caviar Lipid Complex', 'Mirrors the skin\'s natural lipid matrix — replenishes the barrier depleted by environmental exposure and the active phases of the ritual.' ),
				array( 'Triple-Weight Hyaluronic Acid Complex', 'Three molecular weights of hyaluronic acid, for hydration that feels immediate and lasts through the day.' ),
				array( 'Swiss Glacier Meltwater', 'Ultra-pure Swiss glacier meltwater forms the base of this cream.' ),
				array( 'Superoxide Dismutase', 'An antioxidant enzyme that helps defend the skin against the free radicals generated by UV and pollution.' ),
			),
			'how' => array(
				'The final step — apply after serum and eye cream.',
				'Warm a pea-sized amount between palms for 3 seconds.',
				'Press gently across face and neck to seal in the steps before it.',
				'Morning: wear under SPF. Evening: the last step before sleep.',
			),
			'stats' => array(
				array( '100%', 'Panellist-reported satisfaction at 28 days (self-reported)' ),
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
