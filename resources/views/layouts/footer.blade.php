<!-- resources/views/layouts/footer.blade.php -->
<footer class="bg-white border-t mt-8">
  <div class="max-w-7xl mx-auto px-4 py-4 text-center text-sm text-gray-600">
    <div>© {{ date('Y') }} Mohammad Sadman Chowdhury — Built with ❤️</div>
    <div class="mt-1">
      <a href="{{ route('privacy') }}" class="underline">Privacy</a> ·
      <a href="{{ route('about') }}" class="underline">About</a> ·
      <a href="{{ route('contact') }}" class="underline">Contact</a>
    </div>
  </div>
</footer>
