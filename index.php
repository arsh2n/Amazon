<?php
/**
 * SafeToy Picks - Toy Catalogue & Safety Ratings
 * A parent-facing resource for choosing safe, age-appropriate toys.
 */

// ---------------------------------------------------------
// DATA: Toy catalogue with safety info
// In a real deployment, this would come from a database.
// ---------------------------------------------------------
$toys = [
    [
        'id' => 1,
        'name' => 'Wooden Stacking Rings',
        'category' => 'Infant & Toddler',
        'min_age' => 0,
        'max_age' => 2,
        'price' => 14.99,
        'safety_score' => 9.5,
        'choking_hazard' => false,
        'certifications' => ['ASTM F963', 'EN71', 'CE'],
        'material' => 'Solid beechwood, non-toxic paint',
        'description' => 'Large, easy-grip rings that build hand-eye coordination. No small parts, rounded edges throughout.',
        'image_emoji' => '🟡'
    ],
    [
        'id' => 2,
        'name' => 'Building Block Set (250pc)',
        'category' => 'Preschool',
        'min_age' => 3,
        'max_age' => 8,
        'price' => 34.99,
        'safety_score' => 8.7,
        'choking_hazard' => true,
        'certifications' => ['ASTM F963', 'EN71'],
        'material' => 'ABS plastic, BPA-free',
        'description' => 'Classic interlocking blocks for creative building. Contains small pieces — supervise children under 3.',
        'image_emoji' => '🧱'
    ],
    [
        'id' => 3,
        'name' => 'Remote Control Race Car',
        'category' => 'Electronic',
        'min_age' => 6,
        'max_age' => 12,
        'price' => 29.99,
        'safety_score' => 8.0,
        'choking_hazard' => false,
        'certifications' => ['ASTM F963', 'FCC', 'CE'],
        'material' => 'Hard plastic shell, lithium battery pack',
        'description' => 'Durable RC car with parental speed-lock mode. Battery compartment is screw-secured.',
        'image_emoji' => '🚗'
    ],
    [
        'id' => 4,
        'name' => 'Plush Bear (Organic Cotton)',
        'category' => 'Infant & Toddler',
        'min_age' => 0,
        'max_age' => 3,
        'price' => 19.99,
        'safety_score' => 9.8,
        'choking_hazard' => false,
        'certifications' => ['ASTM F963', 'OEKO-TEX', 'CE'],
        'material' => 'Organic cotton, hypoallergenic stuffing',
        'description' => 'Machine-washable, embroidered features (no buttons or small attachments), reinforced seams.',
        'image_emoji' => '🧸'
    ],
    [
        'id' => 5,
        'name' => 'Science Experiment Kit',
        'category' => 'Educational',
        'min_age' => 8,
        'max_age' => 14,
        'price' => 39.99,
        'safety_score' => 7.5,
        'choking_hazard' => false,
        'certifications' => ['ASTM F963', 'CE'],
        'material' => 'Mixed: plastic tools, chemical sachets',
        'description' => 'Hands-on chemistry experiments. Contains mild reagents — adult supervision required for all experiments.',
        'image_emoji' => '🧪'
    ],
    [
        'id' => 6,
        'name' => 'Mini Magnetic Tile Set',
        'category' => 'Preschool',
        'min_age' => 3,
        'max_age' => 10,
        'price' => 27.99,
        'safety_score' => 6.5,
        'choking_hazard' => true,
        'certifications' => ['ASTM F963'],
        'material' => 'Plastic tiles with embedded magnets',
        'description' => 'Popular for STEM play. Caution: loose or damaged tiles can release small magnets — check regularly for cracks.',
        'image_emoji' => '🧲'
    ],
    [
        'id' => 7,
        'name' => 'Balance Bike',
        'category' => 'Outdoor & Active',
        'min_age' => 2,
        'max_age' => 5,
        'price' => 59.99,
        'safety_score' => 9.0,
        'choking_hazard' => false,
        'certifications' => ['ASTM F963', 'EN71', 'CE'],
        'material' => 'Aluminum frame, foam-padded seat',
        'description' => 'No pedals, helps toddlers learn balance before pedaling. Adjustable seat height. Helmet recommended.',
        'image_emoji' => '🚲'
    ],
    [
        'id' => 8,
        'name' => 'Water Balloon Launcher',
        'category' => 'Outdoor & Active',
        'min_age' => 6,
        'max_age' => 14,
        'price' => 17.99,
        'safety_score' => 7.0,
        'choking_hazard' => false,
        'certifications' => ['ASTM F963'],
        'material' => 'Latex balloons, elastic tubing',
        'description' => 'Outdoor fun for groups. Note: contains latex — avoid for children with latex allergies.',
        'image_emoji' => '🎈'
    ],
];

// ---------------------------------------------------------
// LOGIC: Filtering by age and category (via GET params)
// ---------------------------------------------------------
$selected_age = isset($_GET['age']) ? (int)$_GET['age'] : null;
$selected_category = isset($_GET['category']) ? trim($_GET['category']) : 'All';

$categories = ['All'];
foreach ($toys as $t) {
    if (!in_array($t['category'], $categories)) {
        $categories[] = $t['category'];
    }
}

function passes_filters($toy, $age, $category) {
    if ($age !== null && $age > 0) {
        if ($age < $toy['min_age'] || $age > $toy['max_age']) {
            return false;
        }
    }
    if ($category !== 'All' && $toy['category'] !== $category) {
        return false;
    }
    return true;
}

$filtered_toys = array_filter($toys, function ($t) use ($selected_age, $selected_category) {
    return passes_filters($t, $selected_age, $selected_category);
});

function safety_badge_class($score) {
    if ($score >= 9) return 'badge-excellent';
    if ($score >= 7.5) return 'badge-good';
    if ($score >= 6) return 'badge-fair';
    return 'badge-caution';
}

function safety_badge_label($score) {
    if ($score >= 9) return 'Excellent';
    if ($score >= 7.5) return 'Good';
    if ($score >= 6) return 'Fair';
    return 'Use Caution';
}

$total_toys = count($toys);
$avg_safety = round(array_sum(array_column($toys, 'safety_score')) / $total_toys, 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SafeToy Picks — Toy Reviews &amp; Safety Ratings for Parents</title>
<meta name="description" content="Trusted toy reviews and safety ratings to help parents choose age-appropriate, safe toys for their children.">
<style>
  :root {
    --navy: #1e3a5f;
    --teal: #2a9d8f;
    --teal-light: #e8f5f3;
    --amber: #e9a13b;
    --red: #d8584f;
    --green: #4c9a6a;
    --bg: #f7f9fb;
    --card-bg: #ffffff;
    --text-main: #243447;
    --text-muted: #5c6b7a;
    --border: #e1e7ec;
    --radius: 12px;
  }

  * { box-sizing: border-box; }

  body {
    margin: 0;
    font-family: 'Segoe UI', Roboto, -apple-system, sans-serif;
    background: var(--bg);
    color: var(--text-main);
    line-height: 1.55;
  }

  header.site-header {
    background: linear-gradient(135deg, var(--navy), #2c4d75);
    color: #fff;
    padding: 28px 20px;
  }

  .header-inner {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
  }

  .brand {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.5rem;
    font-weight: 700;
  }

  .brand-icon {
    font-size: 1.8rem;
  }

  .tagline {
    font-size: 0.9rem;
    color: #c9d8e8;
    margin-top: 2px;
  }

  .stats-strip {
    display: flex;
    gap: 24px;
    font-size: 0.85rem;
    color: #d7e3ee;
  }

  .stats-strip strong {
    color: #fff;
    font-size: 1.1rem;
    display: block;
  }

  main {
    max-width: 1100px;
    margin: 0 auto;
    padding: 28px 20px 60px;
  }

  .intro {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px 22px;
    margin-bottom: 24px;
  }

  .intro h2 {
    margin: 0 0 6px;
    font-size: 1.1rem;
    color: var(--navy);
  }

  .intro p {
    margin: 0;
    color: var(--text-muted);
    font-size: 0.95rem;
  }

  .filters {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px 22px;
    margin-bottom: 24px;
  }

  .filters form {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    align-items: flex-end;
  }

  .field {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .field label {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--text-muted);
  }

  .field select, .field input {
    padding: 9px 12px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.95rem;
    background: #fff;
    color: var(--text-main);
    min-width: 160px;
  }

  .filters button {
    background: var(--teal);
    color: #fff;
    border: none;
    padding: 10px 22px;
    border-radius: 8px;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
  }

  .filters button:hover { background: #228377; }

  .filters .clear-link {
    font-size: 0.85rem;
    color: var(--text-muted);
    text-decoration: none;
    align-self: center;
    padding-bottom: 11px;
  }

  .results-meta {
    font-size: 0.9rem;
    color: var(--text-muted);
    margin-bottom: 16px;
  }

  .toy-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
  }

  .toy-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }

  .toy-card-top {
    background: var(--teal-light);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.2rem;
    height: 110px;
  }

  .toy-card-body {
    padding: 16px 18px 18px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex-grow: 1;
  }

  .toy-name {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--navy);
    margin: 0;
  }

  .toy-category {
    font-size: 0.78rem;
    color: var(--teal);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.03em;
  }

  .toy-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 4px;
  }

  .toy-age {
    font-size: 0.85rem;
    color: var(--text-muted);
  }

  .toy-price {
    font-weight: 700;
    color: var(--navy);
  }

  .badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 700;
    width: fit-content;
  }

  .badge-excellent { background: #e3f3e8; color: var(--green); }
  .badge-good { background: #eaf3fb; color: #2d6da3; }
  .badge-fair { background: #fdf3e2; color: #b07a1f; }
  .badge-caution { background: #fbe9e7; color: var(--red); }

  .toy-desc {
    font-size: 0.88rem;
    color: var(--text-muted);
    margin: 4px 0 0;
  }

  .toy-extra {
    font-size: 0.8rem;
    color: var(--text-muted);
    border-top: 1px solid var(--border);
    margin-top: 8px;
    padding-top: 8px;
  }

  .toy-extra strong { color: var(--text-main); }

  .choking-flag {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: var(--red);
    font-weight: 600;
    margin-top: 4px;
  }

  .cert-tags {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 6px;
  }

  .cert-tag {
    background: #eef2f6;
    color: var(--text-muted);
    font-size: 0.72rem;
    padding: 3px 8px;
    border-radius: 6px;
  }

  .no-results {
    text-align: center;
    padding: 50px 20px;
    color: var(--text-muted);
    background: var(--card-bg);
    border-radius: var(--radius);
    border: 1px solid var(--border);
  }

  .safety-guide {
    margin-top: 40px;
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 22px 26px;
  }

  .safety-guide h2 {
    color: var(--navy);
    margin-top: 0;
    font-size: 1.2rem;
  }

  .guide-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 18px;
    margin-top: 14px;
  }

  .guide-item h3 {
    margin: 0 0 4px;
    font-size: 0.95rem;
    color: var(--navy);
  }

  .guide-item p {
    margin: 0;
    font-size: 0.86rem;
    color: var(--text-muted);
  }

  footer {
    text-align: center;
    padding: 24px 20px;
    color: var(--text-muted);
    font-size: 0.82rem;
  }

  @media (max-width: 600px) {
    .stats-strip { display: none; }
    .filters form { flex-direction: column; align-items: stretch; }
    .field select, .field input { min-width: 100%; }
  }
</style>
</head>
<body>

<header class="site-header">
  <div class="header-inner">
    <div>
      <div class="brand"><span class="brand-icon">🛡️</span> SafeToy Picks</div>
      <div class="tagline">Honest toy reviews &amp; safety ratings for parents</div>
    </div>
    <div class="stats-strip">
      <div><strong><?php echo $total_toys; ?></strong>Toys Reviewed</div>
      <div><strong><?php echo $avg_safety; ?>/10</strong>Avg. Safety Score</div>
      <div><strong>3rd-Party</strong>Tested Standards</div>
    </div>
  </div>
</header>

<main>

  <div class="intro">
    <h2>Find the right toy, with confidence</h2>
    <p>Every toy below is reviewed against international safety standards (ASTM F963, EN71, CE) and rated for choking hazards, materials, and age-appropriateness. Use the filters to narrow results by your child's age and toy category.</p>
  </div>

  <div class="filters">
    <form method="GET" action="">
      <div class="field">
        <label for="age">Child's age</label>
        <select name="age" id="age">
          <option value="0" <?php echo ($selected_age === null || $selected_age === 0) ? 'selected' : ''; ?>>Any age</option>
          <?php for ($a = 0; $a <= 14; $a++): ?>
            <option value="<?php echo $a; ?>" <?php echo ($selected_age === $a) ? 'selected' : ''; ?>>
              <?php echo $a; ?> <?php echo $a === 0 ? '(under 1)' : ($a === 1 ? 'year' : 'years'); ?>
            </option>
          <?php endfor; ?>
        </select>
      </div>

      <div class="field">
        <label for="category">Category</label>
        <select name="category" id="category">
          <?php foreach ($categories as $cat): ?>
            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($selected_category === $cat) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($cat); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <button type="submit">Apply Filters</button>
      <a class="clear-link" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">Clear filters</a>
    </form>
  </div>

  <p class="results-meta">
    Showing <strong><?php echo count($filtered_toys); ?></strong> of <?php echo $total_toys; ?> toys
    <?php if ($selected_age && $selected_age > 0): ?> for age <strong><?php echo $selected_age; ?></strong><?php endif; ?>
    <?php if ($selected_category !== 'All'): ?> in <strong><?php echo htmlspecialchars($selected_category); ?></strong><?php endif; ?>
  </p>

  <?php if (count($filtered_toys) === 0): ?>
    <div class="no-results">
      <p>No toys match those filters yet. Try a different age or category.</p>
    </div>
  <?php else: ?>
    <div class="toy-grid">
      <?php foreach ($filtered_toys as $toy): ?>
        <div class="toy-card">
          <div class="toy-card-top"><?php echo $toy['image_emoji']; ?></div>
          <div class="toy-card-body">
            <span class="toy-category"><?php echo htmlspecialchars($toy['category']); ?></span>
            <h3 class="toy-name"><?php echo htmlspecialchars($toy['name']); ?></h3>

            <div class="toy-meta-row">
              <span class="toy-age">Ages <?php echo $toy['min_age']; ?>–<?php echo $toy['max_age']; ?></span>
              <span class="toy-price">$<?php echo number_format($toy['price'], 2); ?></span>
            </div>

            <span class="badge <?php echo safety_badge_class($toy['safety_score']); ?>">
              ★ <?php echo $toy['safety_score']; ?>/10 — <?php echo safety_badge_label($toy['safety_score']); ?>
            </span>

            <?php if ($toy['choking_hazard']): ?>
              <div class="choking-flag">⚠️ Contains small parts</div>
            <?php endif; ?>

            <p class="toy-desc"><?php echo htmlspecialchars($toy['description']); ?></p>

            <div class="toy-extra">
              <strong>Material:</strong> <?php echo htmlspecialchars($toy['material']); ?>
              <div class="cert-tags">
                <?php foreach ($toy['certifications'] as $cert): ?>
                  <span class="cert-tag"><?php echo htmlspecialchars($cert); ?></span>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="safety-guide">
    <h2>📋 Quick Toy Safety Guide for Parents</h2>
    <div class="guide-grid">
      <div class="guide-item">
        <h3>Check age labels</h3>
        <p>Age recommendations account for choking hazards and developmental skill, not just difficulty. Always follow the manufacturer's minimum age.</p>
      </div>
      <div class="guide-item">
        <h3>Use the toilet-paper-tube test</h3>
        <p>If a toy or part fits through a toilet paper tube, it's a choking risk for children under 3.</p>
      </div>
      <div class="guide-item">
        <h3>Inspect regularly</h3>
        <p>Check toys periodically for cracks, loose parts, or fraying — especially battery compartments and magnetic pieces.</p>
      </div>
      <div class="guide-item">
        <h3>Look for certifications</h3>
        <p>Standards like ASTM F963 (US), EN71 (EU), and CE marking indicate the toy passed safety testing.</p>
      </div>
      <div class="guide-item">
        <h3>Mind the batteries</h3>
        <p>Button batteries are extremely dangerous if swallowed. Ensure compartments are screw-secured, not just snap-shut.</p>
      </div>
      <div class="guide-item">
        <h3>Supervise as needed</h3>
        <p>Some great toys still need active supervision — chemistry kits, water toys, and anything with small magnets.</p>
      </div>
    </div>
  </div>

</main>

<footer>
  <p>SafeToy Picks &copy; <?php echo date('Y'); ?> — Independent toy safety reviews for parents. Always supervise children during play.</p>
</footer>

</body>
</html>
