<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;
use App\Repositories\UserRepository;
use App\Models\User;

class AuthController extends Controller
{
    protected $user;

    /**
     * Contructor
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->user = $userRepository;
    }

    public function index()
    {
        return view('index');
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(string $provider)
    {
        $userInfo = Socialite::driver($provider)->user();
        $user = $this->getAuthenticateUser($provider, $userInfo);
        auth()->login($user, 1);

        return redirect('/');
    }

    /**
     * Handle authenticate socialte user
     *
     * @var string $provider
     * @var array $userInfo
     *
     * @return redirect
     */
    private function getAuthenticateUser(string $provider, $userInfo)
    {
        switch ($provider) {
            case 'google':
                $userInfo = $this->extractGoogleUserInfo($userInfo);
                break;
            case 'facebook':
                $userInfo = $this->extractFacebookUserInfo($userInfo);
                break;
            case 'github':
                $userInfo = $this->extractGithubUserInfo($userInfo);
                break;
            default:
                $userInfo = $this->extractGoogleUserInfo($userInfo);
                break;
        }

        $user = $this->user->getBySocialiteInfo($userInfo['socialite_id'], $provider);
        if ($user) {
            $user->update(['avatar' => $userInfo['avatar']]);
        } else {
            $user = User::create($userInfo);
        }

        return $user;
    }

    /**
     * Extract email address from email
     *
     * @var strint $email
     *
     * @return string
     */
    private function extractEmailAddress($email)
    {
        return explode('@', $email)[0];
    }

    /**
     * Extract google user info
     *
     * @var array $user
     *
     * @return array
     */
    private function extractGoogleUserInfo($user)
    {
        $username = 'google-' . $this->extractEmailAddress($user->email);

        return [
            'hash_id' => substr(md5($username), 0, 10),
            'socialite_id' => $user->id,
            'socialite_provider' => 'google',
            'username' => $username,
            'email' => $user->email,
            'avatar' => $user->avatar,
        ];
    }

    /**
     * Extract facebook user info
     *
     * @var array $user
     *
     * @return array
     */
    private function extractFacebookUserInfo($user)
    {
        $username = 'facebook-' . $this->extractEmailAddress($user->email);

        return [
            'hash_id' => substr(md5($username), 0, 10),
            'socialite_id' => $user->id,
            'socialite_provider' => 'facebook',
            'username' => $username,
            'email' => $user->email,
            'avatar' => $user->avatar,
        ];
    }

    /**
     * Extract github user info
     *
     * @var array $user
     *
     * @return array
     */
    private function extractGithubUserInfo($user)
    {
        $username = 'github-' . $this->extractEmailAddress($user->email);

        return [
            'hash_id' => substr(md5($username), 0, 10),
            'socialite_id' => $user->id,
            'socialite_provider' => 'github',
            'username' => $username,
            'email' => $user->email,
            'avatar' => $user->avatar,
        ];
    }
}
