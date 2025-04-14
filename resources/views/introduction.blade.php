<x-app-layout>
    <title>Introduction</title>
    <body>
        <h1 class="text-4xl font-bold text-center my-6">Introduction</h1>
        <div class="items-container">
            <div class="item-details flex" style="width: 30%">

                {{-- 3 alap kép a boltról --}}
                <img src="{{ asset('images/front.png') }}">
                <img src="{{ asset('images/porcelains.png') }}">
                <img src="{{ asset('images/street.png') }}">

                {{-- egy google mapses iframe ami mutatja a bolt helyét a térképen --}}
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1613.0483731815452!2d19.054998606340423!3d47.48952541684002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4741dd7635a19e7d%3A0xc66d2f24c6de07e5!2sMarianna%20Present!5e1!3m2!1shu!2shu!4v1733308013521!5m2!1shu!2shu" class="flex-1" style="border-radius: 8px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" id="map"></iframe>
            </div>
            <div class="item-details flex-auto  flex flex-col justify-between">
                <div class="description-details">
                    {{-- Égetett szöveg a bolt történetéről --}}
                    <p style="font-weight: normal">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris ut accumsan ligula, accumsan laoreet lacus. Nulla vitae vehicula libero. Nullam eget dui at nunc aliquam luctus et vel massa. Praesent ultrices odio sit amet ultrices scelerisque. Duis rhoncus imperdiet erat, sodales condimentum felis eleifend nec. Quisque porttitor mollis placerat. Phasellus dolor massa, fringilla in diam nec, ultricies sollicitudin urna. Duis scelerisque hendrerit libero, vitae sagittis mi tincidunt quis. Curabitur sit amet nisi ac neque fermentum sagittis sed vitae tortor. Praesent auctor leo purus, ut viverra nisl posuere eu.<br><br>

                        Quisque venenatis sollicitudin odio, sed hendrerit sem malesuada et. Morbi tincidunt tempor libero nec aliquet. Vestibulum a orci ut magna efficitur tempus. Ut eu consequat nulla. Morbi quam sem, molestie nec nulla at, venenatis sollicitudin ligula. Donec vitae nisl sit amet neque tincidunt aliquam. Suspendisse ultricies sapien a porttitor consectetur. Sed viverra, lectus eget tincidunt pulvinar, ligula sapien fermentum nisi, ac luctus elit orci eget massa. Etiam eu volutpat tortor. Sed eget turpis ultrices lorem consectetur condimentum sit amet vel ligula. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eleifend enim lacus, id vulputate risus cursus et. Aenean egestas dolor risus, in finibus justo dignissim sit amet.<br><br>

                        Quisque orci tortor, porta interdum aliquam at, tempus vitae ipsum. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nullam eget justo eu lacus dignissim suscipit mattis in quam. Curabitur fermentum magna vel sapien suscipit tempus ullamcorper a nulla. In non urna posuere, luctus tortor non, consequat libero. Vestibulum auctor nibh nisi, eu vehicula neque consectetur in. Pellentesque sem ipsum, iaculis id varius id, finibus at ex. In ut nunc blandit, tempus magna vel, condimentum urna. Quisque id dapibus dui, ornare efficitur erat. Nunc tincidunt eleifend nulla eget feugiat. Nam felis nisi, consequat sit amet nulla ac, auctor molestie lectus. Aliquam nec fringilla ex. Curabitur porttitor scelerisque tristique. Vestibulum quis nibh ac massa dictum pharetra non fermentum libero. Duis risus orci, ornare non magna a, tempor vestibulum enim.<br><br>

                        Aliquam ullamcorper, leo mollis mattis placerat, ipsum lacus tristique eros, vel consequat urna lorem ut dolor. Nam sit amet tortor nec mauris consequat lacinia et sit amet tellus. Ut mattis ultricies dui, non fermentum arcu consequat sit amet. Cras id libero augue. Nullam sed nibh sed risus vulputate tincidunt vitae et ipsum. Aenean scelerisque consectetur erat ut feugiat. Duis finibus nisi vel lacinia blandit. Vestibulum mauris diam, venenatis id felis ac, rhoncus iaculis felis. Curabitur iaculis quam leo, id dignissim eros semper ultricies. Etiam varius eu diam vel accumsan. Nulla varius a sem vel porttitor. Nulla tempor vehicula suscipit. Nulla tempus pulvinar volutpat. Integer et convallis velit.<br><br>

                        Donec blandit vitae magna eu dignissim. Cras orci dui, scelerisque at magna eu, vulputate vestibulum tortor. Maecenas molestie nibh id diam feugiat, sed hendrerit est suscipit. Duis varius lectus eget lectus ullamcorper, vitae ullamcorper enim mollis. Morbi vel tincidunt nisl, quis tincidunt velit. Nunc tincidunt, tortor eget facilisis rhoncus, sem arcu blandit sem, non aliquet arcu mauris eget ipsum. Suspendisse tristique scelerisque augue et condimentum. Morbi ullamcorper dui at dictum tempus. Phasellus non varius tortor. Nulla velit sapien, placerat et velit eget, aliquet accumsan urna. Integer euismod turpis non neque facilisis, ut sodales risus pharetra.<br><br>

                        Etiam odio nisi, molestie vel odio et, tempus pulvinar neque. Maecenas non ipsum ac nunc accumsan egestas. Donec sed tellus dolor. Nullam in dapibus ligula, eget cursus nulla. Curabitur aliquet vitae risus a fermentum. Proin vitae erat tellus. Maecenas faucibus tincidunt lobortis. Vestibulum eu urna non arcu dapibus tincidunt. Suspendisse non faucibus nunc. Ut risus ipsum, dapibus nec purus eu, placerat vestibulum leo. Aenean venenatis tristique velit, vel posuere sapien tincidunt sit amet. Sed vitae ullamcorper mauris.<br><br>

                        In a felis dapibus, blandit libero nec, condimentum massa. Suspendisse vulputate convallis nisi gravida pulvinar. Donec leo ipsum, sodales a ex in, bibendum aliquet ligula. Vivamus ac ligula quis tortor rutrum euismod a id ex. Donec sit amet mi cursus, placerat urna sit amet, ultrices quam. Sed mattis neque urna. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nam hendrerit consequat pulvinar. Etiam in nisl ut libero luctus molestie. Etiam ultrices, arcu vitae scelerisque vehicula, nulla sapien vulputate lorem, ac mollis purus ex et velit. Ut tempus iaculis risus, non congue elit egestas eget. Phasellus tellus turpis, fermentum vel lacus et, dignissim volutpat ipsum. In hac habitasse platea dictumst. Mauris accumsan elit ullamcorper, fringilla ante id, dictum sapien.</p>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>
