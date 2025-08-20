<!-- @if (auth()->check())
  <header class="flex justify-between items-center py-4 px-6 bg-[#F2EAEA] shadow">
    <div class="flex items-center space-x-2">
      <div class="bg-red-400 rounded-full w-8 h-8 flex items-center justify-center text-white font-bold">v</div>
      <span class="text-xl font-semibold">Vinh Lab</span>
    </div>
    <img src="{{ auth()->user()->avt_url ? asset(auth()->user()->avt_url) : asset('/storage/imgs/icons8-male-user-100.png') }}" class="rounded-full w-10 h-10" alt="avatar">
  </header>
@else -->
<header class="flex justify-between items-center py-4 px-6 bg-[#F2EAEA] shadow">
    <div class="flex items-center space-x-2">
        <div class="bg-red-400 rounded-full w-8 h-8 flex items-center justify-center text-white font-bold">v</div>
        <span class="text-xl font-semibold">Vinh Lab</span>
    </div>
</header>