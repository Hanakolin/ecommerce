<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dropdown Menu</title>
</head>
<body>
  <!-- Dropdown Button -->
  <div style="position: relative; display: inline-block;">
    <button onclick="toggleMenu()" 
      style="padding: 10px 20px; border: 1px solid #ddd; background-color: #f4f4f4; cursor: pointer; border-radius: 5px;">
      Help & Support
    </button>

    <!-- Dropdown Menu -->
    <div id="dropdownMenu" 
      style="display: none; position: absolute; top: 100%; left: 0; background-color: #ffffff; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); border: 1px solid #ddd; border-radius: 5px; width: 200px; z-index: 1000;">
      <div style="padding: 10px; border-bottom: 1px solid #eee; cursor: pointer;" onclick="alert('Help Center clicked')">
        Help Center
      </div>
      <div style="padding: 10px; border-bottom: 1px solid #eee; cursor: pointer;" onclick="alert('Contact Customer Care clicked')">
        Contact Customer Care
      </div>
      <div style="padding: 10px; border-bottom: 1px solid #eee; cursor: pointer;" onclick="alert('Shipping & Delivery clicked')">
        Shipping & Delivery
      </div>
      <div style="padding: 10px; border-bottom: 1px solid #eee; cursor: pointer;" onclick="alert('Payment clicked')">
        Payment
      </div>
      <div style="padding: 10px; cursor: pointer;" onclick="alert('Order clicked')">
        Order
      </div>
    </div>
  </div>

  <!-- JavaScript to Toggle Dropdown -->
  <script>
    function toggleMenu() {
      const menu = document.getElementById('dropdownMenu');
      if (menu.style.display === 'none' || menu.style.display === '') {
        menu.style.display = 'block';
      } else {
        menu.style.display = 'none';
      }
    }

    // Optional: Close dropdown if clicking outside of it
    document.addEventListener('click', function(event) {
      const menu = document.getElementById('dropdownMenu');
      const button = event.target.closest('button');
      if (!button && menu.style.display === 'block') {
        menu.style.display = 'none';
      }
    });
  </script>
</body>
</html>
