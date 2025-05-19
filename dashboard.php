<!DOCTYPE html>
<html lang="en">

<head>
  <title>Sales Dashboard</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    canvas {
      border: 1px solid #ccc;
      margin-top: 20px;
    }

    .chart-title {
      margin-top: 30px;
    }

    #customAlert {
      display: none;
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #4CAF50;
      color: white;
      padding: 15px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      z-index: 1000;
    }
  </style>
</head>

<body class="bg-light text-dark">
  <div id="customAlert"></div>
  <div class="container py-4">
    <h1 class="text-center mb-4">Real-Time Sales Dashboard</h1>

    <!-- Message -->
    <div id="message" class="text-center mb-3 fw-semibold"></div>

    <!-- Order Form -->
    <div class="card mb-4">
      <div class="card-header bg-primary text-white">Place Order</div>
      <div class="card-body">
        <form id="orderForm" class="row g-3">
          <div class="col-md-4">
            <input type="number" name="product_id" class="form-control" min="1" placeholder="Product ID (1-6)">
          </div>
          <div class="col-md-4">
            <input type="number" name="quantity" class="form-control" min="1" placeholder="Quantity">
          </div>
          <div class="col-md-4">
            <input type="number" step="0.01" name="price" min="0" class="form-control" placeholder="Price">
          </div>
          <div class="col-12 d-grid mt-2">
            <button class="btn btn-success">Submit Order</button>
          </div>
        </form>
      </div>
    </div>
    <div class="text-center mb-3">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recommendationModal">
        Show Recommendations
      </button>
    </div>
    <!-- Top Products Chart -->
    <div class="row text-center">
      <div class="col-md-6">
        <h4 class="chart-title">Top Products by Sales</h4>
        <canvas id="salesChart" width="450" height="300"></canvas>
      </div>

      <!-- Revenue Chart -->
      <div class="col-md-6">
        <h4 class="chart-title">Revenue Overview</h4>
        <canvas id="pieChart" width="350" height="350"></canvas>
      </div>
    </div>
  </div>

  <div class="modal fade" id="recommendationModal" tabindex="-1" aria-labelledby="recommendationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="recommendationModalLabel">Recommendations</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="recommendationContent">
        <div class="text-center">Loading...</div>
      </div>
    </div>
  </div>
</div>

  <script>
    const form = document.getElementById('orderForm');
    // const analyticsDisplay = document.getElementById('analytics');
    const message = document.getElementById('message');
    const chart = document.getElementById('salesChart');
    const ctx = chart.getContext('2d');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(form);
      const jsonData = Object.fromEntries(formData.entries());

      try {
        const res = await fetch('/realtime_sale/index.php?route=orders', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(jsonData)
        });

        const text = await res.text();
        let result;
        try {
          result = JSON.parse(text);
        } catch (err) {
          showMessage('Invalid server response', 'danger');
          console.error('Error JSON:', text);
          return;
        }

        if (result.status) {
          showMessage(result.message, 'success');
          form.reset();
          //fetchAnalytics();
        } else {
          showMessage(result.message || 'Failed to place order', 'danger');
        }

      } catch (error) {
        showMessage('Network error. Please try again.', 'danger');
        console.error(error);
      }
    });

    function orderAlert(msg) {
      const alertBox = document.getElementById('customAlert');
      alertBox.textContent = msg;
      alertBox.style.display = 'block';

      setTimeout(() => {
        alertBox.style.display = 'none';
      }, 3000);
    }

    function showMessage(msg, type = 'info') {
      message.className = `text-${type}`;
      message.textContent = msg;
    }

    async function fetchAnalytics() {
      try {
        const res = await fetch('/realtime_sale/index.php?route=analytics');
        const data = await res.json();

        if (data.status && Array.isArray(data.data) && data.data.length > 0) {
          const analytics = data.data[0];

          if (analytics.top_products.length > 0) {
            drawChart(analytics.top_products);
          }
          drawPieChart(analytics);
        }
      } catch (error) {
        console.error(error);
      }
    }
    
    function drawChart(products) {
      ctx.clearRect(0, 0, chart.width, chart.height);

      const labels = products.map(p => `P${p.product_id}`);
      const values = products.map(p => p.total_quantity);
      const max = Math.max(...values);
      const barWidth = 50;
      const gap = 20;
      const offset = 40;

      labels.forEach((label, i) => {
        const barHeight = (values[i] / max) * 200;

        ctx.fillStyle = '#0d6efd';
        ctx.fillRect(offset + i * (barWidth + gap), chart.height - barHeight - 20, barWidth, barHeight);

        ctx.fillStyle = '#000';
        ctx.fillText(label, offset + i * (barWidth + gap) + 10, chart.height - 5);
      });
    }

    function drawPieChart(data) {
      const pieCanvas = document.getElementById('pieChart');
      const pieCtx = pieCanvas.getContext('2d');
      pieCtx.clearRect(0, 0, pieCanvas.width, pieCanvas.height);

      const values = [{
          label: 'Total Revenue',
          value: data.total_revenue,
          color: '#198754'
        },
        {
          label: 'Last Min Revenue',
          value: data.revenue_last_minute,
          color: '#ffc107'
        },
        {
          label: 'Orders Last Min',
          value: data.orders_last_minute,
          color: '#AF3E3E'
        }
      ];

      console.log('Pie slices:', values);


      const total = values.reduce((sum, item) => sum + item.value, 0) || 1;
      let startAngle = 0;

      values.forEach(item => {
        if (item.value <= 0) return;

        const sliceAngle = (item.value / total) * 2 * Math.PI;

        pieCtx.beginPath();
        pieCtx.moveTo(150, 150);
        pieCtx.arc(150, 150, 100, startAngle, startAngle + sliceAngle);
        pieCtx.closePath();
        pieCtx.fillStyle = item.color;
        pieCtx.fill();

        startAngle += sliceAngle;
      });

      // Draw legend
      pieCtx.font = '12px Arial';
      let labelY = 270;
      values.forEach(item => {
        pieCtx.fillStyle = item.color;
        pieCtx.fillRect(10, labelY - 10, 10, 10);
        pieCtx.fillStyle = '#000';
        pieCtx.fillText(`${item.label}: ${item.value}`, 25, labelY);
        labelY += 20;
      });
    }

    fetchAnalytics();
    
    //Recommendations
    document.getElementById('recommendationModal').addEventListener('shown.bs.modal', async () => {
      const content = document.getElementById('recommendationContent');
      content.innerHTML = '<div class="text-center">Loading...</div>';


      try {
        const res = await fetch('/realtime_sale/index.php?route=recommendations');
        const data = await res.json();
        console.log(data);

        if (data.status && Array.isArray(data.data) && data.data.length > 0) {
          const formatted = data.data[0]
            .replace(/\n/g, '<br>')
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/### (.*)/g, '<h5>$1</h5>');

          content.innerHTML = `<div>${formatted}</div>`;
        } else {
          content.innerHTML = `<div class="alert alert-warning">No recommendations found.</div>`;
        }
      } catch (err) {
        console.log(JSON.stringify(err, null, 2));
        content.innerHTML = `<div class="alert alert-danger">Failed to load recommendations. Please try again.</div>`;
      }
    });

    //Websocket Implentation..
    const socket = new WebSocket('ws://localhost:8080');

    socket.onopen = () => {
      console.log('WebSocket connected');
    };

    socket.onmessage = (event) => {
      try {
        const data = JSON.parse(event.data);
        console.log('WebSocket message:', data);

        if (data.type === 'new_order') {
          fetchAnalytics();
          orderAlert('The new order has been placed');
        }
      } catch (err) {
        console.error('Invalid WebSocket data:', event.data);
      }
    };

    socket.onerror = (error) => {
      console.error('WebSocket error:', error);
    };

    socket.onclose = () => {
      console.warn('WebSocket closed. Try reconnecting later.');
    };
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>