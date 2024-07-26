import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Link, Head, useForm } from '@inertiajs/react';
import axios from 'axios';
import { useState } from 'react';

export default function Welcome() {

    const [user, setUser] = useState(null);
    const [message, setMessage] = useState('');
    const [loading, setLoading] = useState(false);
    const [confirmed, setConfirmed] = useState(false);
    const { data, setData, reset } = useForm({
        email: '',
    });

    const submit = (e) => {
        e.preventDefault();
        setLoading(true);
        setConfirmed(false);
        axios.post('/api/verify', data).then(res => {
            setLoading(false);
            setConfirmed(true);
            setUser(res.data.user)
        }).catch(e => {
            setMessage('Something went wrong! Try again')
        })
    };

    return (
        <>
            <Head title="Welcome" />
            <div className="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
              
                <div className="max-w-7xl mx-auto p-6 flex-1">
                    <div className='bg-white md:w-2/4 lg:w-2/5 rounded p-3 mx-auto'>
                        <h4 className='font-bold text-2xl'>Confirm your Details</h4>
                        <p className='mb-6 text-slate-500'>Kindly fill in the form below to proceed</p>

                        <p className='text-red-500'>{message}</p>
                        <form onSubmit={submit} className='w-full'>
                            <div>
                                <InputLabel htmlFor="email" value="Email Or Phone Number" />
                                <TextInput
                                    id="email"
                                    type="text"
                                    name="email"
                                    value={data.email}
                                    className="mt-1 block w-full"
                                    isFocused={true}
                                    onChange={(e) => setData('email', e.target.value)}
                                />
                            </div>

                            <div className='mt-10'>
                                {
                                    user && confirmed ? <h4 className='text-green-500'>Your details have been verified 
                                        &nbsp; <strong>{user?.name}</strong>
                                    </h4> : ''
                                }

                                {
                                    !user && confirmed ? <h4 className='text-red-500'>
                                        Oops! We not not verify your details {data.email}
                                    </h4> : ''
                                }
                            </div>

                            <div className='text-end'>
                                <PrimaryButton className="mt-4" disabled={loading}>
                                    Verify
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <style>{`
                .bg-dots-darker {
                    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
                }
                @media (prefers-color-scheme: dark) {
                    .dark\\:bg-dots-lighter {
                        background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
                    }
                }
            `}</style>
        </>
    );
}
