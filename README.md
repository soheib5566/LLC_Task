<h1>Application Submission</h1>

<h2>Tech Stack</h2>
<ul>
  <li>Laravel 11</li>
  <li>MySQL/SQLite</li>
  <li>Queue driver: <strong>database</strong></li>
  <li>Mail: <strong>SMTP (Mailtrap recommended)</strong></li>
  <li>PDF: <code>barryvdh/laravel-dompdf</code></li>
  <li>Frontend: Blade + vanilla JS (AJAX)</li>
</ul>

<h2>Installation</h2>
<pre><code>
cp .env.example .env
composer install
php artisan key:generate
</code></pre>

<h3>Configure <code>.env</code></h3>

<p><strong>Database</strong></p>
<pre><code>DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=llc_task
DB_USERNAME=root
DB_PASSWORD=Your_DB_Password
</code></pre>

<p><strong>Queue</strong></p>
<pre><code>QUEUE_CONNECTION=database
</code></pre>

<p><strong>Mail (Mailtrap example)</strong></p>
<pre><code>MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxxxxxxxxxxxxx
MAIL_PASSWORD=xxxxxxxxxxxxxx
MAIL_FROM_ADDRESS="system@example.com"
MAIL_FROM_NAME="LLC"
MAIL_ADMIN_ADDRESS="Your_Email@example.com"
</code></pre>

<h3>Migrate &amp; Prepare</h3>
<pre><code>php artisan migrate
php artisan storage:link
# optional (if you have front-end assets)
npm install
npm run dev
</code></pre>

<h2>Run</h2>
<pre><code># app
php artisan serve
