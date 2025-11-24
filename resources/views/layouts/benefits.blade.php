<div class="relative h-[65rem] z-0" data-aos="fade-up"
    data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
    <img class="absolute z-0 object-cover w-full h-full " src="{{asset('images/homepage/SYGLOW-BG-2.png')}}" alt="">
    <div class="container relative max-w-screen-xl px-4 mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <div class="flex justify-center">
                    <img class="absolute z-0 w-[40rem] h-[15rem]" src="{{asset('images/AboutUs.png')}}" alt="">
                </div>
                <div class="py-20">
                    <h1 class="text-xl text-[#f590b0]">BENEFITS</h1>
                    <h1 class="text-6xl">
                        Why Choose SY Glow?
                    </h1>
                </div>
                <div>
                    <img src="{{asset('images/billboards/billboard_2.jpg')}}" alt="">
                </div>
            </div>
            <div class="py-40">
                <ul class="space-y-4">
                    <li class="flex items-center space-x-4">
                        <img src="{{asset('images/cute1.png')}}" alt="">
                        <div>
                            <h1 class="font-bold">Brightening & Glowing Skin</h1>
                            <p>Achieve a luminous, even-toned complexion with our brightening range.</p>
                        </div>
                    </li>

                    <li class="flex items-center space-x-4">
                        <img src="{{asset('images/cute2.png')}}" alt="">
                        <div>
                            <h1 class="font-bold">Deep Hydration & Nourishment:</h1>
                            <p>
                                Experience long-lasting hydration and nourishment for a healthy, supple appearance.
                            </p>
                        </div>
                    </li>

                    <li class="flex items-center space-x-4">
                        <img src="{{asset('images/cute3.png')}}" alt="">
                        <div>
                            <h1 class="font-bold">Gentle & Effective:</h1>
                            <p>Get powerful results without harsh chemicals or irritation.</p>
                        </div>
                    </li>

                    <li class="flex items-center space-x-4">
                        <img src="{{asset('images/cute4.png')}}" alt="">
                        <div>
                            <h1 class="font-bold">Clean & Cruelty-Free:</h1>
                            <p>
                                Feel good about the products you use with our commitment to clean, cruelty-free formulations.
                            </p>
                        </div>
                    </li>
                </ul>
                <button id="discoverMoreBtn" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-12 py-2 text-white my-12">
                    DISCOVER MORE
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="discoverMoreModal" class="fixed inset-0 hidden items-center justify-center bg-gray-900 bg-opacity-75 z-50">
    <div class="bg-white p-8 rounded-lg max-w-3xl mx-auto text-left text-justify" style="max-height: 80vh; overflow-y: auto;">
        <h2 class="text-3xl font-bold mb-4 text-center">SY Glow Success Story</h2>
        <p class="mb-4">
            SY Glow has been a champion of self-transformation among the Filipinos for years since the pandemic started. It's not only providing affordable means to improve one's beauty but also helps everyone to earn at their own pace. No wonder it has gained popularity despite being a newcomer in the beauty industry. Now, with its products, SY Glow is ready to face more exciting opportunities in the future!
        </p>
        <p class="mb-4">
            It started with a need. A need to survive during the most trying time of their lives - COVID 19 Pandemic. With a strong conviction to live and to help the family, Lou Neria M. Putian a.ka. MotherLou (CEO of Pampaputi) found ways. Using her TikTok account that's already been gaining popularity, she found out that people are in desperate need for a product that makes the skin glow. She realized that she stumbled upon a goldmine of opportunities and leveraged products that the people need at the time. 
        </p>
        <p class="mb-4">
            Who would've thought that her child's piggy bank savings would take her startup to greater heights. Initially, they used the funds to produce 100 pcs of lotion and soaps in October 2020. The very first stocks were sold out in minutes. One user's testimony and proof even went viral and has taken the company to another level of success. Since then, there were repeat orders and stocks were always sold out. During the first few months, more and more distributors locally and internationally got hooked on the products instantaneous effects. There were retail and bulk orders from people of various backgrounds. 
        </p>
        <p class="mb-4">
            Of course, successes are not achieved without difficulties interfering along the way. Although the company is savoring its newfound fame and fortune, more and more competitors have launched their own cosmetic startups. These companies offer products that have cheaper price points and are employing more popular endorsers which, in turn, have divided the market. Furthermore, Many have also tried to sink SY Glow's reputation but failed as the company takes pride in the effectivity of its products -- creating more loyal customers in the long run. Thus, SY Glow has been awarded the Loyal customers award and top 1 seller at JT&T delivery service. You see, the fruits of doing clean business can never go wrong.
        </p>
        <p class="mb-4">
            Today, the brand also made smart investments and held charity events to extend the blessings that we received. Moving forward we aim to be one of the top local skincare brands that everyone can trust. We also aim to provide more jobs to everyone especially to working moms and students who need extra income.
        </p>
        <p class="mb-4">
            SY Glow at present produces soaps, lotions, and cosmetic sets whose effects help consumers embody our tagline "Puting Pantay From Head To Toe". We shall always continue to be true to our claims as we have proven that the people's testimonies are the most effective product advertisement there is.. Ultimately, be part of our growing SY Glow Family. Try our products and be the best that you can be.
        </p>
        <p class="mb-4">
            It is indeed undeniable that the success story of SY Glow testifies to the fact that by having great ideas, being driven by passion and having faith in God, any dream can be achieved.
        </p>
        <!-- Add a close button -->
        <div class="flex justify-center">
            <button id="closeModal" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-4 py-2 text-white">
                Close
            </button>
        </div>
    </div>
</div>


<script>
    // Get modal and button elements
    const discoverMoreBtn = document.getElementById('discoverMoreBtn');
    const discoverMoreModal = document.getElementById('discoverMoreModal');
    const closeModal = document.getElementById('closeModal');

    // Open modal when button is clicked
    discoverMoreBtn.addEventListener('click', () => {
        discoverMoreModal.classList.remove('hidden');
        discoverMoreModal.classList.add('flex');
    });

    // Close modal when the close button is clicked
    closeModal.addEventListener('click', () => {
        discoverMoreModal.classList.add('hidden');
        discoverMoreModal.classList.remove('flex');
    });
</script>
