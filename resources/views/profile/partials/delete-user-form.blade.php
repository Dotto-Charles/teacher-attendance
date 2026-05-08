<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Futa Akaunti
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Mara akaunti yako itakapofutwa, rasilimali zake zote na data yake yote itafutwa kabisa. Kabla ya kufuta akaunti yako, tafadhali pakua data au taarifa yoyote unayotaka kuhifadhi.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Futa Akaunti</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Una uhakika unataka kufuta akaunti yako?
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Mara akaunti yako itakapofutwa, rasilimali zake zote na data yake yote itafutwa kabisa. Tafadhali ingiza nywila yako ili kuthibitisha unataka kufuta akaunti yako kabisa.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Nywila" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Nywila"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Ghairi
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    Futa Akaunti
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
